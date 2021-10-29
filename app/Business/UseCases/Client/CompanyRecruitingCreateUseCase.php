<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateInitializeOutputPort;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateStoreInputPort;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateStoreOutputPort;
use App\Business\Services\ListCreateTrait;
use App\Business\Services\UseCreateJobApplicationTrait;
use App\Business\Services\UseLoggedInCompanyAccountTrait;
use App\Domain\Entities\Company;
use App\Domain\Entities\JobApplication;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class CompanyRecruitingCreateUseCase
 *
 * @package App\Business\UseCases\Client
 */
class CompanyRecruitingCreateUseCase implements CompanyRecruitingCreateInitializeInteractor,CompanyRecruitingCreateStoreInteractor
{
    use UseLoggedInCompanyAccountTrait, ListCreateTrait, UseCreateJobApplicationTrait;

    /**
     * @var JobTypeRepository
     */
    private $jobTypeRepository;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var companyAccountRepository
     */
    private $companyAccountRepository;

    /**
     * @var JobApplicationRepository
     */
    private $jobApplicationRepository;

    /**
     * CompanyRecruitingCreateUseCase constructor.
     *
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     * @param CompanyRepository $companyRepository
     * @param CompanyAccountRepository $companyAccountRepository
     * @param JobApplicationRepository $jobApplicationRepository
     */
    public function __construct(
        JobTypeRepository $jobTypeRepository,
        PrefectureRepository $prefectureRepository,
        CompanyRepository $companyRepository,
        CompanyAccountRepository $companyAccountRepository,
        JobApplicationRepository $jobApplicationRepository
    ) {
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->companyRepository = $companyRepository;
        $this->companyAccountRepository = $companyAccountRepository;
        $this->jobApplicationRepository = $jobApplicationRepository;
    }

    /**
     * 初期化する
     *
     * @param CompanyRecruitingCreateInitializeInputPort $inputPort
     * @param CompanyRecruitingCreateInitializeOutputPort $outputPort
     * @throws BusinessException
     */
    public function initialize(CompanyRecruitingCreateInitializeInputPort $inputPort, CompanyRecruitingCreateInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // ログイン済み企業アカウントIDから企業を取得
        $inputPort->loggedInCompanyAccountId;
        $companyAccount = $this->companyAccountRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->loggedInCompanyAccountId,
                ]
            )
        );
        $company = $companyAccount->getCompany();

        $this->canAddJobApplication($company);

        // 職種リスト
        $outputPort->jobTypeList = $this->createJobTypeList();

        // 都道府県リスト
        $outputPort->prefectureList = $this->createPrefectureList();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録する
     *
     * @param CompanyRecruitingCreateStoreInputPort $inputPort
     * @param CompanyRecruitingCreateStoreOutputPort $outputPort
     * @throws BusinessException
     * @throws FatalBusinessException
     */
    public function create(CompanyRecruitingCreateStoreInputPort $inputPort, CompanyRecruitingCreateStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // ログイン済み企業アカウントIDから企業を取得
        $inputPort->loggedInCompanyAccountId;
        $companyAccount = $this->getLoggedInCompanyAccount($inputPort);
        $company = $companyAccount->getCompany();

        $this->canAddJobApplication($company);

        // 求人作成
        $jobApplication = $this->createJobApplication($company, $inputPort);

        // 求人追加
        $company->addJobApplication($jobApplication);

        // 企業保存
        $this->companyRepository->saveOrUpdate($company, true);

        // 登録した求人IDを設定
        $outputPort->jobApplicationsId = $jobApplication->getId();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 求人追加可能判断
     *
     * @param Company $company
     * @throws BusinessException
     */
    private function canAddJobApplication(Company $company): void
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
    }
}
