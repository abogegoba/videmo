<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Admin\JobApplicationCreate\JobApplicationCreateInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationCreate\JobApplicationCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationCreate\JobApplicationCreateInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationCreate\JobApplicationCreateStoreInputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationCreate\JobApplicationCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationCreate\JobApplicationCreateStoreOutputPort;
use App\Business\Services\ListCreateTrait;
use App\Business\Services\UseCreateJobApplicationTrait;
use App\Domain\Entities\JobApplication;
use App\Domain\Entities\JobType;
use App\Domain\Entities\Prefecture;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\Exception;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class JobApplicationCreateUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class JobApplicationCreateUseCase implements JobApplicationCreateInitializeInteractor, JobApplicationCreateStoreInteractor
{
    use ListCreateTrait, UseCreateJobApplicationTrait;

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
     * JobApplicationCreateUseCase constructor.
     *
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(
        JobTypeRepository $jobTypeRepository,
        PrefectureRepository $prefectureRepository,
        CompanyRepository $companyRepository
    )
    {
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * 初期化する
     *
     * @param JobApplicationCreateInitializeInputPort $inputPort
     * @param JobApplicationCreateInitializeOutputPort $outputPort
     */
    public function initialize(JobApplicationCreateInitializeInputPort $inputPort, JobApplicationCreateInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 対象企業リスト作成
        $allCompanyList = $this->companyRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class),
            [
                'id',
                'name',
                'nameKana'
            ]
        );
        $companyList = [];
        foreach ($allCompanyList as $company) {
            $companyList[$company['id']]['name'] = $company['name'];
            $companyList[$company['id']]['nameKana'] = $company['nameKana'];
        }
        $outputPort->companyList = $companyList;

        // 募集職種リスト作成
        $createJobTypeList = $this->createJobTypeList();
        $firstRowInJobTypeList = [];
        $secondRowInJobTypeList = [];
        $thirdRowInJobTypeList = [];
        foreach ($createJobTypeList as $key => $value) {
            if ($key <= floor(count($createJobTypeList) / 3)) {
                $firstRowInJobTypeList[$key] = $value;
            } elseif (floor(count($createJobTypeList) / 3) < $key && $key <= floor(count($createJobTypeList) / 3) * 2) {
                $secondRowInJobTypeList[$key] = $value;
            } else {
                $thirdRowInJobTypeList[$key] = $value;
            }
        }
        $outputPort->firstRowInJobTypeList = $firstRowInJobTypeList;
        $outputPort->secondRowInJobTypeList = $secondRowInJobTypeList;
        $outputPort->thirdRowInJobTypeList = $thirdRowInJobTypeList;

        // 雇用形態リスト作成
        $employmentTypeList = JobApplication::EMPLOYMENT_TYPE_LIST;
        $outputPort->employmentTypeList = $employmentTypeList;

        // 都道府県リスト作成
        $outputPort->prefectureList = $this->createPrefectureList();

        // ステータスリスト作成
        $statusDisplayList = JobApplication::STATUS_LIST;
        $outputPort->statusDisplayList = $statusDisplayList;

        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録する
     *
     * @param JobApplicationCreateStoreInputPort $inputPort
     * @param JobApplicationCreateStoreOutputPort $outputPort
     * @throws BusinessException
     * @throws \ReLab\Commons\Exceptions\FatalBusinessException
     */
    public function store(JobApplicationCreateStoreInputPort $inputPort, JobApplicationCreateStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        try {
            // 対象企業の存在チェック
            $company = $this->companyRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $inputPort->selectedCompanyId,
                    ])
            );
        } catch (ObjectNotFoundException $e) {
            throw new BusinessException('not_found_target_company');
        }

        // 求人掲載可能数判断
        $isJobApplicationAvailableNumberLessThan = $company->isJobApplicationAvailableNumberLessThan(count($company->getJobApplications()));
        if ($isJobApplicationAvailableNumberLessThan === false) {
            // 求人掲載可能数の上限を超えた場合
            throw new BusinessException('can_not_recruiting_create');
        }

        // 求人作成
        $jobApplication = $this->createJobApplication($company, $inputPort);

        $recruitmentWorkLocations = $jobApplication->getRecruitmentWorkLocations();
        $prefectureList = [];
        foreach ($recruitmentWorkLocations as $recruitmentWorkLocation) {
            $prefectureList[] = $recruitmentWorkLocation->getId();
        }
        if (1 < max(array_count_values($prefectureList))) {
            // 同勤務地を選択していた場合
            throw new BusinessException('duplication.recruitment_work_location');
        }

        // 求人追加
        $company->addJobApplication($jobApplication);

        // 企業保存
        $this->companyRepository->saveOrUpdate($company, true);

        // 登録した求人IDを設定
        $outputPort->jobApplicationsId = $jobApplication->getId();

        //ログ出力
        Log::infoOut();
    }
}