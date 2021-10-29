<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\CompanySearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\CompanySearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Criteria\JobTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Front\Top\TopInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\Top\TopInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Top\TopInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\Top\TopSearchInputPort;
use App\Business\Interfaces\Interactors\Front\Top\TopSearchInteractor;
use App\Business\Interfaces\Interactors\Front\Top\TopSearchOutputPort;
use App\Business\Services\AreaGetTrait;
use App\Utilities\Log;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class TopUseCase
 *
 * @package App\Business\UseCases\Front
 */
class TopUseCase implements TopInitializeInteractor, TopSearchInteractor
{
    use AreaGetTrait;

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
    public function __construct(BusinessTypeRepository $businessTypeRepository,JobTypeRepository $jobTypeRepository,PrefectureRepository $prefectureRepository, CompanyRepository $companyRepository)
    {
        $this->businessTypeRepository = $businessTypeRepository;
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * 初期表示
     *
     * @param TopInitializeInputPort $inputPort
     * @param TopInitializeOutputPort $outputPort
     */
    public function initialize(TopInitializeInputPort $inputPort, TopInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 業種リスト
        $businessTypes = $this->businessTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id","name"]
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
            CriteriaFactory::getInstance()->create(JobTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id","name"]
        );
        $jobTypesNameList = array_column($jobTypes, "name");
        $jobTypesIdList = array_column($jobTypes, "id");
        $outputPort->jobTypeList = array_combine ($jobTypesIdList , $jobTypesNameList);

        // エリアリスト（地方）
        $outputPort->prefectureList = $this->getAreaList();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 検索画面へ条件保持して遷移
     *
     * @param TopSearchInputPort $inputPort
     * @param TopSearchOutputPort $outputPort
     */
    public function search(TopSearchInputPort $inputPort, TopSearchOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //ログ出力
        Log::infoOut();
    }
}
