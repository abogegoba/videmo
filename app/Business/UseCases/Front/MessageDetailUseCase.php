<?php

namespace App\Business\UseCases\Front;

use App\Adapters\Gateways\Criteria\MessageDetailSearchDoctrineCriteria;
use App\Business\Interfaces\Gateways\Criteria\MessageSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MessageSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MessageDetailSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\MessageRepository;
use App\Business\Interfaces\Gateways\Repositories\ModelSentenceRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Front\MessageDetail\MessageDetailInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\MessageDetail\MessageDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MessageDetail\MessageDetailInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\MessageDetail\MessageDetailSendInputPort;
use App\Business\Interfaces\Interactors\Front\MessageDetail\MessageDetailSendInteractor;
use App\Business\Interfaces\Interactors\Front\MessageDetail\MessageDetailSendOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\Company;
use App\Domain\Entities\Message;
use App\Domain\Entities\ModelSentence;
use App\Domain\Entities\UserAccount;
use App\Utilities\Log;
use Carbon\Carbon;
use Pusher\Pusher;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;
use ReLab\Commons\Wrappers\Transaction;
use ReLab\Doctrine\Criteria\GeneralDoctrineCriteria;

/**
 * Class MessageDetailUseCase
 *
 * @package App\Business\UseCases\Front
 */
class MessageDetailUseCase implements MessageDetailInitializeInteractor, MessageDetailSendInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * @var ModelSentenceRepository
     */
    private $modelSentenceRepository;

    /**
     * MessageDetailUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param UserAccountRepository $userAccountRepository
     * @param CompanyRepository $companyRepository
     * @param MessageRepository $messageRepository
     * @param ModelSentenceRepository $modelSentenceRepository
     */
    public function __construct(
        MemberRepository $memberRepository,
        UserAccountRepository $userAccountRepository,
        CompanyRepository $companyRepository,
        MessageRepository $messageRepository,
        ModelSentenceRepository $modelSentenceRepository
    )
    {
        $this->memberRepository = $memberRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->companyRepository = $companyRepository;
        $this->messageRepository = $messageRepository;
        $this->modelSentenceRepository = $modelSentenceRepository;
    }

    /**
     * ????????????
     *
     * @param MessageDetailInitializeInputPort $inputPort
     * @param MessageDetailInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(MessageDetailInitializeInputPort $inputPort, MessageDetailInitializeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        $url = $inputPort->url;
        $path = explode("/", $url);
        $last = end($path);

        if ($last === "detail-request") {
            $outputPort->requestFlg = true;
            $outputPort->request = '?????????????????????????????????????????????????????????????????????????????????????????????????????????' . "\n" . '????????????????????????????????????????????????????????????';
        }

        $member = $this->getLoggedInMember($inputPort);

        $loggedInUserAccount = $member->getUserAccount();
        $loggedInUserAccountId = $loggedInUserAccount->getId();
        $outputPort->loggedInUserAccountId = $loggedInUserAccountId;

        $criteriaFactory = CriteriaFactory::getInstance();
        $messages = $this->messageRepository->findByCriteria(
            $criteriaFactory->create(MessageSearchCriteria::class, MessageSearchExpressionBuilder::class,
                [
                    "exchangeUserAccountId" => $loggedInUserAccountId
                ]
            )
        );
        $exchangeUserAccountInformationList = [];
        if (!empty($messages)) {
            // ?????????????????????????????????????????????????????????????????????????????????????????????????????? or ???????????????????????????????????????????????????
            foreach ($messages as $message) {
                $exchangeSendingUserAccount = $message->getSendingUserAccount();
                $exchangeSendingUserAccountId = $exchangeSendingUserAccount->getId();
                $exchangeReceivingUserAccount = $message->getReceivingUserAccount();
                $exchangeReceivingUserAccountId = $exchangeReceivingUserAccount->getId();
                if (
                    $exchangeSendingUserAccountId !== $loggedInUserAccountId
                    && !array_key_exists($exchangeSendingUserAccountId, $exchangeUserAccountInformationList)
                ) {
                    // ??????????????????????????????????????????????????????
                    $exchangeUserAccountInformationList[$exchangeSendingUserAccountId] = $this->setMessageInformationForList($exchangeSendingUserAccount, $exchangeSendingUserAccountId, $message);
                    // ?????????????????????????????????????????????????????????????????????????????????????????????
                    $exchangeUserAccountInformationList[$exchangeSendingUserAccountId]["unreadCount"] = $message->getAlreadyRead() ? 0 : 1;
                } elseif (
                    $exchangeReceivingUserAccountId !== $loggedInUserAccountId
                    && $exchangeReceivingUserAccount->isCompanyAccount() === true
                    && !array_key_exists($exchangeReceivingUserAccountId, $exchangeUserAccountInformationList)
                ) {
                    $exchangeUserAccountInformationList[$exchangeReceivingUserAccountId] = $this->setMessageInformationForList($exchangeReceivingUserAccount, $exchangeReceivingUserAccountId, $message);
                    $exchangeUserAccountInformationList[$exchangeReceivingUserAccountId]["latestMessageSendingUserAccountId"] = $exchangeSendingUserAccountId;
                } elseif (
                    array_key_exists($exchangeSendingUserAccountId, $exchangeUserAccountInformationList)
                    && !$message->getAlreadyRead()
                ) {
                    $exchangeUserAccountInformationList[$exchangeSendingUserAccountId]["unreadCount"]++;
                } else {
                    // Do Nothing.
                }
            }
            $outputPort->exchangeUserAccountInformationList = $exchangeUserAccountInformationList;
        }

        $companyUserAccountId = $inputPort->companyUserAccountId;

        if (!$companyUserAccountId) {
            Log::infoOut();
            return;
        }

        try {
            // ??????????????????????????????????????????
            $userAccount = $this->userAccountRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralDoctrineCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $companyUserAccountId
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("data_not_found");
        }
        $companyUserAccount = $userAccount->getCompanyAccount();
        $company = $companyUserAccount->getCompany();

        // ??????????????????
        $companyLogoImage = $company->getCompanyLogoImage();
        $companyLogoFilePath = '';
        if (isset($companyLogoImage)) {
            $companyLogoFilePath = $companyLogoImage->getFilePathForFrontShow();
        }
        $outputPort->companyLogoFilePath = $companyLogoFilePath;

        // ???????????????
        $outputPort->name = $company->getName();

        $criteriaFactory = CriteriaFactory::getInstance();
        $messages = $this->messageRepository->findByCriteria(
            $criteriaFactory->create(MessageDetailSearchDoctrineCriteria::class, MessageDetailSearchExpressionBuilder::class,
                [
                    "opponentUserAccountId" => $companyUserAccountId,
                    "oneselfUserAccountId" => $loggedInUserAccountId
                ]
            )
        );

        $exchangeMessageList = [];
        foreach ($messages as $message) {
            $receivingUserAccountId = $message->getReceivingUserAccount()->getId();
            $sendingUserAccountId = $message->getSendingUserAccount()->getId();
            if ($sendingUserAccountId === $companyUserAccountId && $receivingUserAccountId === $loggedInUserAccountId) {
                // ???????????????????????????????????????????????????????????????????????????????????????
                // ?????????????????????
                $alreadyRead = $message->getAlreadyRead();
                if ($alreadyRead === false) {
                    $message->setAlreadyRead(true);
                    $this->messageRepository->saveOrUpdate($message, true);
                }
                $exchangeMessageList[] = $this->setMessageInfomationForDetail($companyUserAccountId, $companyLogoFilePath, $message);
            } elseif ($sendingUserAccountId === $loggedInUserAccountId && $receivingUserAccountId === $companyUserAccountId) {
                // ?????????????????????????????????
                $exchangeMessageList[] = $this->setMessageInfomationForDetail($loggedInUserAccountId, null, $message);
            }
        }
        $outputPort->exchangeMessageList = $exchangeMessageList;

        $outputPort->companyUserAccountId = $companyUserAccountId;

        $outputPort->messageSendToName = $company->getName();

        $outputPort->companyDetailUrl = route("front.company.detail", ["companyId" => $company->getId()]);

        $modelSentences = $this->modelSentenceRepository->findByCriteria(
            $criteriaFactory->create(GeneralDoctrineCriteria::class, GeneralExpressionBuilder::class,
                [
                    "modelSentenceType" => ModelSentence::FOR_STUDENTS
                ]
            )
        );
        $modelSentenceNameList = [];
        $modelSentenceContentList = [];
        foreach ($modelSentences as $modelSentence){
            $modelSentenceNameList[] = $modelSentence->getName();
            $modelSentenceContentList[] = $modelSentence->getContent();
        }
        $outputPort->modelSentenceNameList = ['' => "????????????????????????"]+$modelSentenceNameList;
        $outputPort->modelSentenceContentList = ['' => ""]+$modelSentenceContentList;

        //????????????
        Log::infoOut();
    }

    /**
     * ??????????????????????????????
     *
     * @param MessageDetailSendInputPort $inputPort
     * @param MessageDetailSendOutputPort $outputPort
     * @throws BusinessException
     * @throws FatalBusinessException
     */
    public function sendMessage(MessageDetailSendInputPort $inputPort, MessageDetailSendOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        $member = $this->getLoggedInMember($inputPort);
        $loggedInUserAccount = $member->getUserAccount();
        $loggedInUserAccountId = $loggedInUserAccount->getId();
        $companyUserAccountId = $inputPort->companyUserAccountId;

        $criteriaFactory = CriteriaFactory::getInstance();
        $messages = $this->messageRepository->findByCriteria(
            $criteriaFactory->create(MessageDetailSearchDoctrineCriteria::class, MessageDetailSearchExpressionBuilder::class,
                [
                    "opponentUserAccountId" => $companyUserAccountId,
                    "oneselfUserAccountId" => $loggedInUserAccountId
                ]
            )
        );
        if (empty($messages)) {
            $criteriaFactory = CriteriaFactory::getInstance();
            $messagesByCompanies = $this->messageRepository->findByCriteria(
                $criteriaFactory->create(MessageDetailSearchDoctrineCriteria::class, MessageDetailSearchExpressionBuilder::class,
                    [
                        "oneselfUserAccountId" => $loggedInUserAccountId
                    ]
                )
            );
            $sendMessageToCompanyIdList = [];
            foreach ($messagesByCompanies as $message) {
                // ??????????????????????????????
                $sendMessageToCompany = $this->getSendMessageToCompany($message);
                if ($sendMessageToCompany !== null) {
                    $sendMessageToCompanyIdList[] = $sendMessageToCompany->getId();
                }
            }
            // ?????????????????????????????????????????????????????????????????????30???
            if (count(array_unique($sendMessageToCompanyIdList)) >= 30) {
                throw new BusinessException('can_not_send_message');
            }
        }

        try {
            $companyUserAccount = $this->userAccountRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralDoctrineCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $companyUserAccountId
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("data_not_found");
        }

        $sendTime = Transaction::getInstance()->getDateTime();

        $message = new Message();
        $message->setSendingUserAccount($loggedInUserAccount);
        $message->setContent($inputPort->messageToSend);
        $message->setContributionDatetime($sendTime);
        $message->setAlreadyRead(false);
        $message->setStatus(Message::STATUS_DISPLAY);
        $message->setReceivingUserAccount($companyUserAccount);

        // ????????????
        $this->messageRepository->saveOrUpdate($message, true);

        // ??????????????????????????????????????????
        $template = "mail.front.member.message_send_mail";
        $mailAddress = $companyUserAccount->getMailAddress();
        $lastName = $loggedInUserAccount->getMember()->getLastName();
        $firstName = $loggedInUserAccount->getMember()->getFirstName();
        $sendUserName = $lastName . " " . $firstName;
        $companyName = $companyUserAccount->getCompanyAccount()->getCompany()->getName();
        $companyUserLastName = $companyUserAccount->getCompanyAccount()->getLastName();
        $companyUserFirstName = $companyUserAccount->getCompanyAccount()->getFirstName();
        $companyUserName = $companyUserLastName . " " . $companyUserFirstName;
        $title = "???LinkT???" . $sendUserName . " ?????????????????????????????????????????????";
        $dataList["content"] = $inputPort->messageToSend;
        $dataList["sendUserName"] = $sendUserName;
        $dataList["sendTime"] = $sendTime->format("Y/n/j H:i:s");
        $dataList["companyName"] = $companyName;
        $dataList["companyUserName"] = $companyUserName;
        $dataList["clientLoginURL"] = env('CLIENT_LOGIN_URL');
        $data = Data::wrap($dataList);

        $mail = Mail::getInstance($template, $mailAddress, trans($title), $data);
        $mailResult = $mail->send();

        if ($mailResult !== true) {
            throw new FatalBusinessException("not_send_mail");
        }

        //pusher
        $options = [
            'cluster' => 'ap3',
            'useTLS' => true
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $idPhoto = $loggedInUserAccount->getMember()->getIdPhotoFilePathForClientShow();
        $privatePhoto = $loggedInUserAccount->getMember()->getPrivatePhotoFilePathForClientShow();
        if($idPhoto != asset('/img/mypage/profile/img_self.png') && $privatePhoto != asset('/img/mypage/profile/img_self.png')){
            $idPass = $idPhoto;
        }elseif($idPhoto != asset('/img/mypage/profile/img_self.png') && $privatePhoto == asset('/img/mypage/profile/img_self.png')){
            $idPass = $idPhoto;
        }elseif($idPhoto == asset('/img/mypage/profile/img_self.png') && $privatePhoto != asset('/img/mypage/profile/img_self.png')){
            $idPass = $privatePhoto;
        }else{
            $idPass = asset('/img/mypage/profile/img_self.jpg');
        }

        $data = [
            'from' => $loggedInUserAccountId,
            'to' => $companyUserAccountId,
            'notification_type' => 'message',
            'name' => $sendUserName,
            'user_photo' => $idPass,
            'sending_date' => date('Y-m-d H:i:s')
        ];

        $pusher->trigger('my-client-channel','my-client-event',$data);

        // ????????????
        Log::infoOperationCreateLog("", ["message" => (array)$message], "");

        $outputPort->companyUserAccountId = $companyUserAccountId;

        //????????????
        Log::infoOut();
    }

    /**
     * ??????????????????????????????????????????
     *
     * @param UserAccount $userAccount
     * @param $userAccountId
     * @param Message $message
     * @return array
     */
    private function setMessageInformationForList(UserAccount $userAccount, int $userAccountId, Message $message)
    {
        return [
            'messageDetailUrl' => route("front.message.detail", ["userAccountId" => $userAccountId]),
            'companyLogo' => $this->getCompanyLogoFilePath($userAccount),
            'companyName' => $userAccount->getCompanyAccount()->getCompany()->getName(),
            'content' => $message->getContent(),
            'contributionDatetime' => $message->getContributionDatetime()->format('Y/n/j H:i:s'),
            'alreadyRead' => $message->getAlreadyRead()
        ];
    }

    /**
     * ???????????????????????????????????????
     *
     * @param int $uerAccountId
     * @param null|string $logo
     * @param Message $message
     * @return array
     */
    private function setMessageInfomationForDetail(int $uerAccountId, ?string $logo, Message $message)
    {
        return [
            'sendingUserAccountId' => $uerAccountId,
            'companyLogo' => $logo,
            'content' => $message->getContent(),
            'contributionDatetime' => $message->getContributionDatetime()->format('Y/n/j H:i:s'),
            'alreadyRead' => true,
            'id' => $message->getId(),
            'status' => $message->getStatus(),
        ];
    }

    /**
     * ??????????????????????????????????????????
     *
     * @param UserAccount $userAccount
     * @return null|string
     */
    private function getCompanyLogoFilePath(UserAccount $userAccount)
    {
        if ($userAccount->isCompanyAccount() === true) {
            if($userAccount->getCompanyAccount()->getCompany()->getCompanyLogoImage() != null){
                $companyLogoFilePath = $userAccount->getCompanyAccount()->getCompany()->getCompanyLogoImage()->getFilePathForFrontShow();
            }else{
                $companyLogoFilePath = asset('/img/common/no_image_logo.png');
            }
        } else {
            return null;
        }
        return $companyLogoFilePath;
    }

    /**
     * ??????????????????????????????
     *
     * @param Message $message
     * @return Company|null
     */
    private function getSendMessageToCompany(Message $message): ?Company
    {
        $receivingUserAccount = $message->getReceivingUserAccount();
        $companyAccount = $receivingUserAccount->getCompanyAccount();
        $sendMessageToCompany = null;
        if ($companyAccount !== null) {
            // ????????????????????????
            $today = Carbon::today();
            if (Carbon::now()->startOfMonth() <= $today && $today <= Carbon::now()->endOfMonth()) {
                $sendMessageToCompany = $companyAccount->getCompany();
            }
        }

        return $sendMessageToCompany;
    }
}
