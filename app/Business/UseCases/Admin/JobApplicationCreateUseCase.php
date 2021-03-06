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
     * ???????????????
     *
     * @param JobApplicationCreateInitializeInputPort $inputPort
     * @param JobApplicationCreateInitializeOutputPort $outputPort
     */
    public function initialize(JobApplicationCreateInitializeInputPort $inputPort, JobApplicationCreateInitializeOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        // ???????????????????????????
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

        // ???????????????????????????
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

        // ???????????????????????????
        $employmentTypeList = JobApplication::EMPLOYMENT_TYPE_LIST;
        $outputPort->employmentTypeList = $employmentTypeList;

        // ???????????????????????????
        $outputPort->prefectureList = $this->createPrefectureList();

        // ??????????????????????????????
        $statusDisplayList = JobApplication::STATUS_LIST;
        $outputPort->statusDisplayList = $statusDisplayList;

        //????????????
        Log::infoOut();
    }

    /**
     * ????????????
     *
     * @param JobApplicationCreateStoreInputPort $inputPort
     * @param JobApplicationCreateStoreOutputPort $outputPort
     * @throws BusinessException
     * @throws \ReLab\Commons\Exceptions\FatalBusinessException
     */
    public function store(JobApplicationCreateStoreInputPort $inputPort, JobApplicationCreateStoreOutputPort $outputPort): void
    {
        //????????????
        Log::infoIn();

        try {
            // ?????????????????????????????????
            $company = $this->companyRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $inputPort->selectedCompanyId,
                    ])
            );
        } catch (ObjectNotFoundException $e) {
            throw new BusinessException('not_found_target_company');
        }

        // ???????????????????????????
        $isJobApplicationAvailableNumberLessThan = $company->isJobApplicationAvailableNumberLessThan(count($company->getJobApplications()));
        if ($isJobApplicationAvailableNumberLessThan === false) {
            // ????????????????????????????????????????????????
            throw new BusinessException('can_not_recruiting_create');
        }

        // ????????????
        $jobApplication = $this->createJobApplication($company, $inputPort);

        $recruitmentWorkLocations = $jobApplication->getRecruitmentWorkLocations();
        $prefectureList = [];
        foreach ($recruitmentWorkLocations as $recruitmentWorkLocation) {
            $prefectureList[] = $recruitmentWorkLocation->getId();
        }
        if (1 < max(array_count_values($prefectureList))) {
            // ???????????????????????????????????????
            throw new BusinessException('duplication.recruitment_work_location');
        }

        // ????????????
        $company->addJobApplication($jobApplication);

        // ????????????
        $this->companyRepository->saveOrUpdate($company, true);

        // ??????????????????ID?????????
        $outputPort->jobApplicationsId = $jobApplication->getId();

        //????????????
        Log::infoOut();
    }
}