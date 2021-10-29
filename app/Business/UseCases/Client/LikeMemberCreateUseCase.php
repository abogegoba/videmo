<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\LikeMemberRepository;
use App\Business\Interfaces\Interactors\Client\LikeMemberCreate\LikeMemberCreateInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\LikeMemberCreate\LikeMemberCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\LikeMemberCreate\LikeMemberCreateInitializeOutputPort;
use App\Business\Interfaces\Interactors\Client\LikeMemberCreate\LikeMemberCreateStoreInputPort;
use App\Business\Interfaces\Interactors\Client\LikeMemberCreate\LikeMemberCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Client\LikeMemberCreate\LikeMemberCreateStoreOutputPort;
use App\Business\Services\UseLoggedInCompanyAccountTrait;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class LikeMemberCreateUseCase
 *
 * @package App\Business\UseCases\Client
 */
class LikeMemberCreateUseCase implements LikeMemberCreateInitializeInteractor,LikeMemberCreateStoreInteractor
{
    use UseLoggedInCompanyAccountTrait;

    /**
     * @var LikeMemberRepository
     */
    private $likeMemberRepository;

    /**
     * LikeMemberCreateUseCase constructor.
     *
     * @param LikeMemberRepository $likeMemberRepository
     */
    public function __construct(
        LikeMemberRepository $likeMemberRepository
    ) {
        $this->likeMemberRepository = $likeMemberRepository;
    }

    /**
     * 初期化する
     *
     * @param LikeMemberCreateInitializeInputPort $inputPort
     * @param LikeMemberCreateInitializeOutputPort $outputPort
     * @throws BusinessException
     */
    public function initialize(LikeMemberCreateInitializeInputPort $inputPort, LikeMemberCreateInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // ログイン済み企業アカウントIDから企業を取得
        $inputPort->loggedInCompanyAccountId;
        /*$companyAccount = $this->likeMemberRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->loggedInCompanyAccountId,
                ]
            )
        );
        $company = $companyAccount->getCompany();

        $this->canAddJobApplication($company);*/

        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録する
     *
     * @param LikeMemberCreateStoreInputPort $inputPort
     * @param LikeMemberCreateStoreOutputPort $outputPort
     * @throws BusinessException
     * @throws FatalBusinessException
     */
    public function create(LikeMemberCreateStoreInputPort $inputPort, LikeMemberCreateStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // ログイン済み企業アカウントIDから企業を取得
        $inputPort->loggedInCompanyAccountId;
        /*$companyAccount = $this->getLoggedInCompanyAccount($inputPort);
        $company = $companyAccount->getCompany();*/

        /*$this->canAddJobApplication($company);*/

        // 求人作成
        /*$jobApplication = $this->createJobApplication($company, $inputPort);*/

        // 求人追加
        /*$likeMember->addJobApplication($jobApplication);*/

        // 企業保存
        $this->likeMemberRepository->saveOrUpdate($inputPort, true);

        // 登録した求人IDを設定
        /*$outputPort->jobApplicationsId = $jobApplication->getId();*/

        //ログ出力
        Log::infoOut();
    }

    /**
     * 求人追加可能判断
     *
     * @param Company $company
     * @throws BusinessException
     */
    /*private function canAddJobApplication(Company $company): void
    {
        $criteriaFactory = CriteriaFactory::getInstance();
        $jobApplications = $this->jobApplicationRepository->findByCriteria(
            $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "company.id" => $company->getId()
                ]
            )
        );
        $jobApplicationAvailableNumber = $company->getJobApplicationAvailableNumber();
        if ($jobApplicationAvailableNumber <= count($jobApplications)) {
            throw new BusinessException('can_not_recruiting_create');
        }
    }*/
}
