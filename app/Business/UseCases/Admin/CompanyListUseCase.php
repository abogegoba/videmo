<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\CompanyListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Criteria\CompanySearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\OperatingCompanyAccountRepository;
use App\Business\Interfaces\Interactors\Admin\CompanyList\CompanyListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyList\CompanyListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyList\CompanyListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyList\CompanyListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\CompanyList\CompanyListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyList\CompanyListSearchOutputPort;
use App\Domain\Entities\Company;
use App\Domain\Entities\JobApplication;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class CompanyListUseCase
 *
 * 企業を一覧する
 *
 * @property  jobApplicationRepository
 * @package App\Business\UseCases\Admin
 */
class CompanyListUseCase implements CompanyListSearchInteractor, CompanyListInitializeInteractor
{
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var OperatingCompanyAccountRepository
     */
    private $operatingCompanyAccountRepository;

    /**
     * @var OperatingCompanyAccountRepository
     */
    private $jobApplicationRepository;

    /**
     * BusinessCompanyListUseCase constructor.
     *
     * @param CompanyRepository $companyRepository
     * @param OperatingCompanyAccountRepository $operatingCompanyAccountRepository
     * @param JobApplicationRepository $jobApplicationRepository
     */
    public function __construct(
        CompanyRepository $companyRepository,
        OperatingCompanyAccountRepository $operatingCompanyAccountRepository,
        JobApplicationRepository $jobApplicationRepository
    ) {
        $this->companyRepository = $companyRepository;
        $this->operatingCompanyAccountRepository = $operatingCompanyAccountRepository;
        $this->jobApplicationRepository = $jobApplicationRepository;
    }

    /**
     * 初期化する
     *
     * @param CompanyListInitializeInputPort $inputPort
     * @param CompanyListInitializeOutputPort $outputPort
     */
    public function initialize(CompanyListInitializeInputPort $inputPort, CompanyListInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // リスト作成
        $outputPort->companyStatusList = Company::COMPANY_STATUS_LIST;
        $outputPort->jobApplicationAvailableNumberList = Company::JOB_APPLICATION_AVAILABLE_NUMBERS;

        //ログ出力
        Log::infoOut();
    }

    /**
     * 検索する
     *
     * @param CompanyListSearchInputPort $inputPort
     * @param CompanyListSearchOutputPort $outputPort
     */
    public function search(CompanyListSearchInputPort $inputPort, CompanyListSearchOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // Inputにページ指定が存在しない場合は新規で作成する
        $pager = $inputPort->pager;
        if (!isset($pager)) {
            $pager = new Class() extends Data implements Pager
            {
            };
        }
        // 1ページ最大件数を設定する
        $pager->limit = self::PAGE_LIMIT_COUNT;
        $outputPort->pager = $pager;

        try {
            $companies = $this->companyRepository->findByCriteria(
                CriteriaFactory::getInstance()->create(CompanySearchCriteria::class, CompanyListSearchExpressionBuilder::class,
                    $inputPort,
                    [
                        "pager" => $pager
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            $companies = [];
        }

        $criteriaFactory = CriteriaFactory::getInstance();
        $companyList = [];
        foreach ($companies as $company) {
            $companyId = $company->getId();
            $jobApplications = $this->jobApplicationRepository->findByCriteria(
                $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "company.id" => $companyId,
                        "status" => JobApplication::STATUS_DISPLAY,
                    ]
                )
            );
            if (!empty($jobApplications)) {
                $jobApplicationCount = count($jobApplications);
            } else {
                $jobApplicationCount = 0;
            }
            $companyList[] = [
                "company" => $company,
                "jobApplicationCount" => $jobApplicationCount,
            ];
        }
        $outputPort->companyList = $companyList;

        //ログ出力
        Log::infoOut();
    }
}