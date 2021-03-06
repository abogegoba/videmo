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
     * ????????????
     *
     * @param AdminMessageDetailInitializeInputPort $inputPort
     * @param AdminMessageDetailInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(AdminMessageDetailInitializeInputPort $inputPort, AdminMessageDetailInitializeOutputPort $outputPort): void
    {
        // ????????????
        Log::infoIn();

        // ?????????????????????
        $memberUserAccountId = $inputPort->memberUserAccountId;
        try {
            // ??????????????????????????????????????????
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

        //??????????????????
        $idImage = $member->getIdPhotoFilePathForClientShow();
        $outputPort->idImage = $idImage;

        // ???????????????
        $memberLastName = $member->getLastName();
        $memberFirstName = $member->getFirstName();
        $memberName = "$memberLastName $memberFirstName";
        $outputPort->memberName = $memberName;


        // ?????????????????????
        $companyUserAccountId = $inputPort->companyUserAccountId;
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
        $outputPort->companyLogo = $companyLogoFilePath;

        // ???????????????
        $companyName = $company->getName();
        $outputPort->companyName = $companyName;


        // ??????????????????????????????????????????????????????
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
                // ???????????????????????????????????????????????????????????????????????????????????????
                $exchangeMessageList[] = $this->setMessageInformation($memberName, $idImage, $message);
            } elseif ($sendingUserAccountId === $companyUserAccountId && $receivingUserAccountId === $memberUserAccountId) {
                // ?????????????????????????????????
                $exchangeMessageList[] = $this->setMessageInformation($companyName, $companyLogoFilePath, $message);
            }
        }

        $outputPort->exchangeMessageList = $exchangeMessageList;

        // ????????????
        Log::infoOut();
    }

    /**
     * ??????????????????????????????
     *
     * @param AdminMessageStatusUpdateInputPort $inputPort
     * @param AdminMessageStatusUpdateOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function update(AdminMessageStatusUpdateInputPort $inputPort, AdminMessageStatusUpdateOutputPort $outputPort): void
    {
        // ????????????
        Log::infoIn();

        $messageId = $inputPort->messageId;
        try {
            // ????????????????????????
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

        // ?????????????????????????????????
        $status = $message->getStatus();

        if($status === 10){
            $message->setStatus(20);
        }elseif($status === 20){
            $message->setStatus(10);
        }

        // ????????????????????????
        $this->messageRepository->saveOrUpdate($message, true);

        // ????????????
        Log::infoOut();
    }


    /**
     * ???????????????????????????????????????
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