<?php


namespace App\Business\UseCases\Admin;


use App\Adapters\Gateways\Criteria\MessageDetailSearchDoctrineCriteria;
use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\AdminMessageDetailSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MessageDetailSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\MessageRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Admin\MessageDetail\AdminMessageDetailInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\MessageDetail\AdminMessageDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MessageDetail\AdminMessageDetailInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\MessageDetail\AdminMessageStatusUpdateInputPort;
use App\Business\Interfaces\Interactors\Admin\MessageDetail\AdminMessageStatusUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\MessageDetail\AdminMessageStatusUpdateOutputPort;
use App\Domain\Entities\Message;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;
use ReLab\Doctrine\Criteria\GeneralDoctrineCriteria;

class AdminMessageDetailUseCase implements AdminMessageDetailInitializeInteractor, AdminMessageStatusUpdateInteractor
{
    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    public function __construct(MemberRepository $memberRepository, UserAccountRepository $userAccountRepository, messageRepository $messageRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * 初期表示
     *
     * @param AdminMessageDetailInitializeInputPort $inputPort
     * @param AdminMessageDetailInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(AdminMessageDetailInitializeInputPort $inputPort, AdminMessageDetailInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 学生側情報取得
        $memberUserAccountId = $inputPort->memberUserAccountId;
        try {
            // 会員のユーザーアカウント取得
            $memberUserAccount = $this->userAccountRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralDoctrineCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $memberUserAccountId
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("data_not_found");
        }
        $member = $memberUserAccount->getMember();

        //証明写真取得
        $idImage = $member->getIdPhotoFilePathForClientShow();
        $outputPort->idImage = $idImage;

        // 会員名取得
        $memberLastName = $member->getLastName();
        $memberFirstName = $member->getFirstName();
        $memberName = "$memberLastName $memberFirstName";
        $outputPort->memberName = $memberName;


        // 企業側情報取得
        $companyUserAccountId = $inputPort->companyUserAccountId;
        try {
            // 企業のユーザーアカウント取得
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

        // 企業ロゴ取得
        $companyLogoImage = $company->getCompanyLogoImage();
        $companyLogoFilePath = '';
        if (isset($companyLogoImage)) {
            $companyLogoFilePath = $companyLogoImage->getFilePathForFrontShow();
        }
        $outputPort->companyLogo = $companyLogoFilePath;

        // 会社名取得
        $companyName = $company->getName();
        $outputPort->companyName = $companyName;


        // 両アカウント間のメッセージ一覧を作成
        $criteriaFactory = CriteriaFactory::getInstance();
        $messages = $this->messageRepository->findByCriteria(
            $criteriaFactory->create(MessageDetailSearchDoctrineCriteria::class, AdminMessageDetailSearchExpressionBuilder::class,
                [
                    "opponentUserAccountId" => $companyUserAccountId,
                    "oneselfUserAccountId" => $memberUserAccountId
                ]
            )
        );

        $exchangeMessageList = [];
        foreach ($messages as $message) {
            $receivingUserAccountId = $message->getReceivingUserAccount()->getId();
            $sendingUserAccountId = $message->getSendingUserAccount()->getId();
            if ($sendingUserAccountId === $memberUserAccountId && $receivingUserAccountId === $companyUserAccountId) {
                // 企業が送ったメッセージの場合（受け取ったメッセージの場合）
                $exchangeMessageList[] = $this->setMessageInformation($memberName, $idImage, $message);
            } elseif ($sendingUserAccountId === $companyUserAccountId && $receivingUserAccountId === $memberUserAccountId) {
                // 学生が送ったメッセージ
                $exchangeMessageList[] = $this->setMessageInformation($companyName, $companyLogoFilePath, $message);
            }
        }

        $outputPort->exchangeMessageList = $exchangeMessageList;

        // ログ出力
        Log::infoOut();
    }

    /**
     * ステータスを更新する
     *
     * @param AdminMessageStatusUpdateInputPort $inputPort
     * @param AdminMessageStatusUpdateOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function update(AdminMessageStatusUpdateInputPort $inputPort, AdminMessageStatusUpdateOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $messageId = $inputPort->messageId;
        try {
            // メッセージを取得
            $message = $this->messageRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralDoctrineCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $messageId
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("data_not_found");
        }

        // 現在のステータスを取得
        $status = $message->getStatus();

        if($status === 10){
            $message->setStatus(20);
        }elseif($status === 20){
            $message->setStatus(10);
        }

        // ステータスを更新
        $this->messageRepository->saveOrUpdate($message, true);

        // ログ出力
        Log::infoOut();
    }


    /**
     * メッセージの情報を設定する
     *
     * @param string $name
     * @param null|string $thumbnail
     * @param Message $message
     * @return array
     */
    private function setMessageInformation(string $name, ?string $thumbnail, Message $message)
    {
        return [
            'name' => $name,
            'thumbnail' => $thumbnail,
            'content' => $message->getContent(),
            'contributionDatetime' => $message->getContributionDatetime()->format('Y/n/j H:i:s'),
            'status' => $message->getStatus(),
            'id' => $message->getId(),
        ];
    }
}