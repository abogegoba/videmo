<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingList\CompanyRecruitingListInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingList\CompanyRecruitingListInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingList\CompanyRecruitingListInitializeOutputPort;
use App\Business\Services\UseLoggedInCompanyAccountTrait;
use App\Utilities\Log;
use App\Domain\Entities\JobApplication;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class CompanyRecruitingListUseCase
 *
 * @package App\Business\UseCases\Client
 */
class CompanyRecruitingListUseCase implements CompanyRecruitingListInitializeInteractor
{
    use UseLoggedInCompanyAccountTrait;

    /**
     * @var JobApplicationRepository
     */
    private $jobApplicationRepository;

    /**
     * @var companyAccountRepository
     */
    private $companyAccountRepository;

    /**
     * MessageListUseCase constructor.
     *
     * @param JobApplicationRepository $jobApplicationRepository
     */
    public function __construct(
        JobApplicationRepository $jobApplicationRepository,
        CompanyAccountRepository $companyAccountRepository

    ) {
        $this->jobApplicationRepository = $jobApplicationRepository;
        $this->companyAccountRepository = $companyAccountRepository;
    }

    /**
     * 初期表示
     *
     * @param CompanyRecruitingListInitializeInputPort $inputPort
     * @param CompanyRecruitingListInitializeOutputPort $outputPort
     */
    public function initialize(CompanyRecruitingListInitializeInputPort $inputPort, CompanyRecruitingListInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // ログイン済み企業アカウントIDから求人を取得
        $inputPort->loggedInCompanyAccountId;
        $companyAccount = $this->getLoggedInCompanyAccount($inputPort);
        $companyId = $companyAccount->getCompany()->getId();
        $criteriaFactory = CriteriaFactory::getInstance();
        $jobApplications = $this->jobApplicationRepository->findByCriteria(
            $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "company.id" => $companyId
                ]
            )
        );
        $companyRecruitingList = [];
        if (!empty($jobApplications)) {
            foreach ($jobApplications as $jobApplication) {
                $companyRecruiting = [];

                // 求人タイトル
                $companyRecruiting['title'] = $jobApplication->getTitle();

                // 求人条件
                $companyRecruiting['jobConditions'] = $this->createJobConditions($jobApplication);

                // 職種
                $companyRecruiting['jobTypeName'] = $this->getJobTypeName($jobApplication);

                $companyRecruitingList[$jobApplication->getid()] = $companyRecruiting;
            }
            $outputPort->companyRecruitingList = $companyRecruitingList;
        }

        //ログ出力
        Log::infoOut();
    }

    /**
     * 求人条件作成
     *
     * @param JobApplication $jobApplication
     * @return string
     */
    private function createJobConditions(JobApplication $jobApplication): string
    {
        $recruitmentWorkLocations = $jobApplication->getRecruitmentWorkLocations();
        $location = '';
        if (!is_null($recruitmentWorkLocations)) {
            foreach ($recruitmentWorkLocations as $recruitmentWorkLocation) {
                $name = $recruitmentWorkLocation->getName();
                if (!empty($name)) {
                    $location = $location . $name . '／';
                }
            }
            if (!empty($location)) {
                $location = mb_substr($location, 0, -1);
            }
        }

        $employmentType = $jobApplication->getEmploymentType();
        $employmentTypeName = '';
        if (!is_null($employmentType)) {
            if ($employmentType === JobApplication::EMPLOYMENT_TYPE_REGULAR) {
                $employmentTypeName = JobApplication::EMPLOYMENT_TYPE_NAME_REGULAR;
            } else if ($employmentType === JobApplication::EMPLOYMENT_TYPE_CONTRACT) {
                $employmentTypeName = JobApplication::EMPLOYMENT_TYPE_NAME_CONTRACT;
            }
        }

        $jobConditions =  '';
        if ($location !== '' && $employmentTypeName !== ''){
            $jobConditions = $location . '、' . $employmentTypeName;
        } else if ($location === '' && $employmentTypeName !== '') {
            $jobConditions = $employmentTypeName;
        } else if ($location !== '' && $employmentTypeName === '') {
            $jobConditions = $location;
        }

        if ($jobConditions !== '') {
            $jobConditions = '（' . $jobConditions . '）';
        }

        return $jobConditions;
    }

    /**
     * 職種名取得
     *
     * @param JobApplication $jobApplication
     * @return string
     */
    private function getJobTypeName(JobApplication $jobApplication): string
    {
        $jobType = $jobApplication->getRecruitmentJobType();
        $jobName = '';
        if (!is_null($jobType)) {
            $jobName = $jobType->getName();
        }

        return $jobName;
    }
}