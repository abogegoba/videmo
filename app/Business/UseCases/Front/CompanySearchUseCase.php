<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\CompanySearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\CompanySearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Criteria\JobTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Front\CompanySearch\CompanySearchInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\CompanySearch\CompanySearchInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\CompanySearch\CompanySearchInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\CompanySearch\CompanySearchInputPort;
use App\Business\Interfaces\Interactors\Front\CompanySearch\CompanySearchInteractor;
use App\Business\Interfaces\Interactors\Front\CompanySearch\CompanySearchOutputPort;
use App\Business\Services\AreaGetTrait;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class CompanySearchUseCase
 *
 * @package App\Business\UseCases\Front
 */
class CompanySearchUseCase implements CompanySearchInitializeInteractor, CompanySearchInteractor
{
    use AreaGetTrait;
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 20;

    /**
     * @var BusinessTypeRepository
     */
    private $businessTypeRepository;

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
     * TopUseCase constructor.
     *
     * @param BusinessTypeRepository $businessTypeRepository
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(BusinessTypeRepository $businessTypeRepository, JobTypeRepository $jobTypeRepository, PrefectureRepository $prefectureRepository, CompanyRepository $companyRepository)
    {
        $this->businessTypeRepository = $businessTypeRepository;
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * 初期表示
     *
     * @param CompanySearchInitializeInputPort $inputPort
     * @param CompanySearchInitializeOutputPort $outputPort
     */
    public function initialize(CompanySearchInitializeInputPort $inputPort, CompanySearchInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        // 業種リスト
        $businessTypes = $this->businessTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $businessTypeNameList = array_column($businessTypes, "name");
        $businessTypeIdList = array_column($businessTypes, "id");
        $businessTypes = array_combine ($businessTypeIdList , $businessTypeNameList);

        $businessTypeList = [];
        foreach($businessTypes as $businessTypeKey => $businessType){
            $companies = $this->companyRepository->findByCriteria(
                CriteriaFactory::getInstance()->create(CompanySearchCriteria::class, CompanySearchExpressionBuilder::class,
                    [
                        "industryCondition" => $businessTypeKey
                    ]
                )
            );
            if(count($companies) != 0){
                $businessTypeList[$businessTypeKey] = $businessType;
            }
        }
        $outputPort->businessTypeList = $businessTypeList;

        // 職種リスト
        $jobTypes = $this->jobTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(JobTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $jobTypesNameList = array_column($jobTypes, "name");
        $jobTypesIdList = array_column($jobTypes, "id");
        $outputPort->jobTypeList = array_combine($jobTypesIdList, $jobTypesNameList);

        // エリアリスト（地方）
        $outputPort->prefectureList = $this->getAreaList();

        // 検索条件が既にある場合セット
        if ($inputPort->isCondition === true) {
            $outputPort->companyNameCondition = $inputPort->companyNameCondition;
            $outputPort->industryCondition = $inputPort->industryCondition;
            $outputPort->jobTypeCondition = $inputPort->jobTypeCondition;
            $outputPort->areaCondition = $inputPort->areaCondition;
            $outputPort->recruitmentTargetConditionThisYear = $inputPort->recruitmentTargetConditionThisYear;
            $outputPort->recruitmentTargetConditionNextYear = $inputPort->recruitmentTargetConditionNextYear;
            $outputPort->recruitmentTargetConditionIntern = $inputPort->recruitmentTargetConditionIntern;
        }

        $outputPort->trackingId = env("GA_TRACKING_ID");

        //ログ出力
        Log::infoOut();
    }

    /**
     * 検索する
     *
     * @param CompanySearchInputPort $inputPort
     * @param CompanySearchOutputPort $outputPort
     */
    public function search(CompanySearchInputPort $inputPort, CompanySearchOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $pager = $inputPort->pager;
        if (!isset($pager)) {
            $pager = new Class() extends Data implements Pager
            {
            };
        }
        $pager->limit = self::PAGE_LIMIT_COUNT;
        $outputPort->pager = $pager;

        $areaNumber = $inputPort->areaCondition;
        $inputPort->areaCondition = $this->AreaChange($areaNumber);

        try {
            $companies = $this->companyRepository->findByCriteria(
                CriteriaFactory::getInstance()->create(CompanySearchCriteria::class, CompanySearchExpressionBuilder::class,
                    $inputPort,
                    [
                        "pager" => $pager,
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            $companies = [];
        }

        $outputPort->companies = $companies;

        //ログ出力
        Log::infoOut();
    }
}
