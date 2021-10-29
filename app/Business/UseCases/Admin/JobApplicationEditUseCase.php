<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Admin\JobApplicationEdit\JobApplicationEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationEdit\JobApplicationEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationEdit\JobApplicationEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationEdit\JobApplicationEditUpdateInputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationEdit\JobApplicationEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationEdit\JobApplicationEditUpdateOutputPort;
use App\Business\Services\ListCreateTrait;
use App\Business\Services\UseCreateJobApplicationTrait;
use App\Domain\Entities\JobApplication;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\Exception;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class JobApplicationEditUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class JobApplicationEditUseCase implements JobApplicationEditInitializeInteractor, JobApplicationEditUpdateInteractor
{
    use ListCreateTrait, UseCreateJobApplicationTrait;

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
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * JobApplicationEditUseCase constructor.
     *
     * @param JobApplicationRepository $jobApplicationRepository
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(
        JobApplicationRepository $jobApplicationRepository,
        JobTypeRepository $jobTypeRepository,
        PrefectureRepository $prefectureRepository,
        CompanyRepository $companyRepository
    ) {
        $this->jobApplicationRepository = $jobApplicationRepository;
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * 初期化する
     *
     * @param JobApplicationEditInitializeInputPort $inputPort
     * @param JobApplicationEditInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(JobApplicationEditInitializeInputPort $inputPort, JobApplicationEditInitializeOutputPort $outputPort): void
    {
        try {
            $jobApplication = $this->jobApplicationRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        'id' => $inputPort->jobApplicationId
                    ])
            );
        } catch (Exception $e) {
            throw new FatalBusinessException('edit_target_not_found');
        }
        $outputPort->jobApplication = $jobApplication;

        $company = $jobApplication->getCompany();
        $outputPort->company = $company;

        $recruitmentJobType = $jobApplication->getRecruitmentJobType();
        $outputPort->jobType = $recruitmentJobType->getId();

        $recruitmentWorkLocations = $jobApplication->getRecruitmentWorkLocations();
        $area1 = $recruitmentWorkLocations[0];
        if (!is_null($area1)) {
            $outputPort->area1 = $area1->getDisplayNumber();
        }

        $area2 = $recruitmentWorkLocations[1];
        if (!is_null($area2)) {
            $outputPort->area2 = $area2->getDisplayNumber();
        }

        $area3 = $recruitmentWorkLocations[2];
        if (!is_null($area3)) {
            $outputPort->area3 = $area3->getDisplayNumber();
        }

        $area4 = $recruitmentWorkLocations[3];
        if (!is_null($area4)) {
            $outputPort->area4 = $area4->getDisplayNumber();
        }

        $area5 = $recruitmentWorkLocations[4];
        if (!is_null($area5)) {
            $outputPort->area5 = $area5->getDisplayNumber();
        }

        $area6 = $recruitmentWorkLocations[5];
        if (!is_null($area6)) {
            $outputPort->area6 = $area6->getDisplayNumber();
        }

        $area7 = $recruitmentWorkLocations[6];
        if (!is_null($area7)) {
            $outputPort->area7 = $area7->getDisplayNumber();
        }

        $area8 = $recruitmentWorkLocations[7];
        if (!is_null($area8)) {
            $outputPort->area8 = $area8->getDisplayNumber();
        }

        $area9 = $recruitmentWorkLocations[8];
        if (!is_null($area9)) {
            $outputPort->area9 = $area9->getDisplayNumber();
        }

        $area10 = $recruitmentWorkLocations[9];
        if (!is_null($area10)) {
            $outputPort->area10 = $area10->getDisplayNumber();
        }

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
    }

    /**
     * 変更する
     *
     * @param JobApplicationEditUpdateInputPort $inputPort
     * @param JobApplicationEditUpdateOutputPort $outputPort
     * @throws BusinessException
     * @throws FatalBusinessException
     */
    public function update(JobApplicationEditUpdateInputPort $inputPort, JobApplicationEditUpdateOutputPort $outputPort): void
    {
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

        try {
            $jobApplication = $this->jobApplicationRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $inputPort->jobApplicationId,
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException('edit_target_not_found');
        }

        $companyRepository = $this->companyRepository;
        $jobTypeRepository = $this->jobTypeRepository;
        Data::mappingToObject($inputPort, $jobApplication, [
            'selectedCompanyId' => function ($value, $inputPort, $toObject) use ($companyRepository) {
                $toObject->setCompany($companyRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                        [
                            'id' => $value
                        ]
                    )
                ));
            },
            'jobType' => function ($value, $inputPort, $toObject) use ($jobTypeRepository) {
                $toObject->setRecruitmentJobType($jobTypeRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                        [
                            'id' => $value
                        ]
                    )
                ));
            }
        ]);

        $prefectureId1 = $inputPort->area1;
        $area1 = $this->getArea($prefectureId1);
        $area2 = null;
        $prefectureId2 = $inputPort->area2;
        if ($prefectureId2 !== null) {
            $area2 = $this->getArea($prefectureId2);
        }
        $area3 = null;
        $prefectureId3 = $inputPort->area3;
        if ($prefectureId3 !== null) {
            $area3 = $this->getArea($prefectureId3);
        }
        $area4 = null;
        $prefectureId4 = $inputPort->area4;
        if ($prefectureId4 !== null) {
            $area4 = $this->getArea($prefectureId4);
        }
        $area5 = null;
        $prefectureId5 = $inputPort->area5;
        if ($prefectureId5 !== null) {
            $area5 = $this->getArea($prefectureId5);
        }
        $area6 = null;
        $prefectureId6 = $inputPort->area6;
        if ($prefectureId6 !== null) {
            $area6 = $this->getArea($prefectureId6);
        }
        $area7 = null;
        $prefectureId7 = $inputPort->area7;
        if ($prefectureId7 !== null) {
            $area7 = $this->getArea($prefectureId7);
        }
        $area8 = null;
        $prefectureId8 = $inputPort->area8;
        if ($prefectureId8 !== null) {
            $area8 = $this->getArea($prefectureId8);
        }
        $area9 = null;
        $prefectureId9 = $inputPort->area9;
        if ($prefectureId9 !== null) {
            $area9 = $this->getArea($prefectureId9);
        }
        $area10 = null;
        $prefectureId10 = $inputPort->area10;
        if ($prefectureId10 !== null) {
            $area10 = $this->getArea($prefectureId10);
        }
        $jobApplication->setRecruitmentWorkLocations(array_filter([$area1, $area2, $area3, $area4, $area5, $area6, $area7, $area8, $area9, $area10]));


        $prefectureList = [];
        foreach ($jobApplication->getRecruitmentWorkLocations() as $recruitmentWorkLocation) {
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
    }

    /**
     * 勤務地を取得する
     *
     * @param string $prefectureId
     * @return \App\Domain\Entities\Prefecture
     */
    private function getArea(string $prefectureId)
    {
        $area = $this->prefectureRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    'id' => $prefectureId
                ]
            )
        );
        return $area;
    }
}