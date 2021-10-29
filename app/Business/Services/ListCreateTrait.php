<?php

namespace App\Business\Services;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\JobTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use ReLab\Commons\Wrappers\CriteriaFactory;

trait ListCreateTrait
{
    /**
     * @var JobTypeRepository
     */
    private $jobTypeRepository;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * @var BusinessTypeRepository
     */
    private $businessTypeRepository;

    /**
     * ListCreateTrait constructor.
     *
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     * @param BusinessTypeRepository $businessTypeRepository
     */
    public function __construct(
        JobTypeRepository $jobTypeRepository,
        PrefectureRepository $prefectureRepository,
        BusinessTypeRepository $businessTypeRepository
    ) {
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->businessTypeRepository = $businessTypeRepository;
    }

    /**
     * @return array|false
     */
    protected function createJobTypeList()
    {
        // 職種リスト
        $jobTypes = $this->jobTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(JobTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $jobTypesNameList = array_column($jobTypes, "name");
        $jobTypesIdList = array_column($jobTypes, "id");
        return $jobTypeList = array_combine($jobTypesIdList, $jobTypesNameList);
    }

    /**
     * @return array|false
     */
    protected function createPrefectureList()
    {
        // 都道府県リスト
        $prefectures = $this->prefectureRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $prefectureNameList = array_column($prefectures, "name");
        $prefectureIdList = array_column($prefectures, "id");
        return $prefectureList = array_combine($prefectureIdList, $prefectureNameList);
    }

    /**
     * @return array|false
     */
    protected function createBusinessTypeList()
    {
        // 業種リスト
        $businessTypes = $this->businessTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $businessTypeNameList = array_column($businessTypes, "name");
        $businessTypeIdList = array_column($businessTypes, "id");
        return array_combine($businessTypeIdList, $businessTypeNameList);
    }
}