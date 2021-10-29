<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit\CompanyRecruitingEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit\CompanyRecruitingEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit\CompanyRecruitingEditInitializeOutputPort;

use App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit\CompanyRecruitingEditStoreInputPort;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit\CompanyRecruitingEditStoreInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit\CompanyRecruitingEditStoreOutputPort;
use App\Business\Services\ListCreateTrait;
use App\Business\Services\UseCreateJobApplicationTrait;
use App\Business\Services\UseLoggedInCompanyAccountTrait;
use App\Business\Services\UseSelectedJobApplicationTrait;
use App\Utilities\Log;
/**
 * Class ProfileUseCase
 *
 * @package App\Business\UseCases\Client
 */
class CompanyRecruitingEditUseCase implements CompanyRecruitingEditInitializeInteractor,CompanyRecruitingEditStoreInteractor
{
    use UseLoggedInCompanyAccountTrait,UseSelectedJobApplicationTrait,ListCreateTrait,UseCreateJobApplicationTrait;

    /**
     * @var JobApplicationRepository
     */
    private $jobApplicationRepository;

    /**
     * @var JobTypeRepository
     */
    private $jobTypeRepository;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * @var companyAccountRepository
     */
    private $companyAccountRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * CompanyRecruitingEditUseCase constructor.
     * @param JobApplicationRepository $jobApplicationRepository
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     */
    public function __construct(
        JobApplicationRepository $jobApplicationRepository,
        JobTypeRepository $jobTypeRepository,
        PrefectureRepository $prefectureRepository,
        CompanyAccountRepository $companyAccountRepository,
        CompanyRepository $companyRepository
    )
    {
        $this->jobApplicationRepository = $jobApplicationRepository;
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->companyAccountRepository = $companyAccountRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * 初期化する
     *
     * @param CompanyRecruitingEditInitializeInputPort $inputPort
     * @param CompanyRecruitingEditInitializeOutputPort $outputPort
     * @throws \ReLab\Commons\Exceptions\FatalBusinessException
     */
    public function initialize(CompanyRecruitingEditInitializeInputPort $inputPort, CompanyRecruitingEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //選択した求人情報IDから求人情報を取得
        $inputPort->selectedJobApplicationsId;
        $jobApplication = $this->getSelectedJobApplication($inputPort);

        //各項目の初期値を渡す
        $outputPort->selectedJobApplicationsId = $inputPort->selectedJobApplicationsId;
        $outputPort->title = $jobApplication->getTitle();

        $jobType = $jobApplication->getRecruitmentJobType();
        if (!is_null($jobType)){
            $outputPort->jobType = $jobType->getId();
        }

        $outputPort->recruitmentJobTypeDescription = $jobApplication->getRecruitmentJobTypeDescription();
        $outputPort->jobDescription = $jobApplication->getJobDescription();
        $outputPort->employmentType = $jobApplication->getEmploymentType();
        $outputPort->statue = $jobApplication->getStatue();
        $outputPort->screeningMethod = $jobApplication->getScreeningMethod();
        $outputPort->compensation = $jobApplication->getCompensation();
        $outputPort->bonus = $jobApplication->getBonus();

        $area1 = $jobApplication->getRecruitmentWorkLocations()[0];
        if (!is_null($area1)){
            $outputPort->area1 = $area1->getDisplayNumber();
        }

        $area2 = $jobApplication->getRecruitmentWorkLocations()[1];
        if (!is_null($area2)){
            $outputPort->area2 = $area2->getDisplayNumber();
        }

        $area3 = $jobApplication->getRecruitmentWorkLocations()[2];
        if (!is_null($area3)){
            $outputPort->area3 = $area3->getDisplayNumber();
        }

        $area4 = $jobApplication->getRecruitmentWorkLocations()[3];
        if (!is_null($area4)){
            $outputPort->area4 = $area4->getDisplayNumber();
        }

        $area5 = $jobApplication->getRecruitmentWorkLocations()[4];
        if (!is_null($area5)){
            $outputPort->area5 = $area5->getDisplayNumber();
        }

        $area6 = $jobApplication->getRecruitmentWorkLocations()[5];
        if (!is_null($area6)){
            $outputPort->area6 = $area6->getDisplayNumber();
        }

        $area7 = $jobApplication->getRecruitmentWorkLocations()[6];
        if (!is_null($area7)){
            $outputPort->area7 = $area7->getDisplayNumber();
        }

        $area8 = $jobApplication->getRecruitmentWorkLocations()[7];
        if (!is_null($area8)){
            $outputPort->area8 = $area8->getDisplayNumber();
        }

        $area9 = $jobApplication->getRecruitmentWorkLocations()[8];
        if (!is_null($area9)){
            $outputPort->area9 = $area9->getDisplayNumber();
        }

        $area10 = $jobApplication->getRecruitmentWorkLocations()[9];
        if (!is_null($area10)){
            $outputPort->area10 = $area10->getDisplayNumber();
        }

        $outputPort->dutyHours = $jobApplication->getDutyHours();
        $outputPort->compensationPackage = $jobApplication->getCompensationPackage();
        $outputPort->nonWorkDay = $jobApplication->getNonWorkDay();
        $outputPort->recruitmentNumber = $jobApplication->getRecruitmentNumber();
        $outputPort->status = $jobApplication->getStatus();

        //職種リスト
        $outputPort->jobTypeList = $this->createJobTypeList();
        //都道府県リスト
        $outputPort->prefectureList = $this->createPrefectureList();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 変更する
     *
     * @param CompanyRecruitingEditStoreInputPort $inputPort
     * @param CompanyRecruitingEditStoreOutputPort $outputPort
     * @throws \ReLab\Commons\Exceptions\FatalBusinessException
     */
    public function edit(CompanyRecruitingEditStoreInputPort $inputPort, CompanyRecruitingEditStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // ログイン済み企業アカウントIDから企業を取得
        $inputPort->loggedInCompanyAccountId;
        $companyAccount = $this->getLoggedInCompanyAccount($inputPort);
        $company = $companyAccount->getCompany();

        $inputPort->selectedJobApplicationsId;

        // 求人更新
        $jobApplication = $this->updateJobApplication($company, $inputPort);

        // 求人追加
        $company->addJobApplication($jobApplication);

        // 企業保存
        $this->companyRepository->saveOrUpdate($company, true);

        //ログ出力
        Log::infoOut();
    }
}