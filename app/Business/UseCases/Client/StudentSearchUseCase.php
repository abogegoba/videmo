<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\MemberSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MemberSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Client\StudentSearch\StudentSearchInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\StudentSearch\StudentSearchInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\StudentSearch\StudentSearchInitializeOutputPort;
use App\Business\Interfaces\Interactors\Client\StudentSearch\StudentSearchInputPort;
use App\Business\Interfaces\Interactors\Client\StudentSearch\StudentSearchInteractor;
use App\Business\Interfaces\Interactors\Client\StudentSearch\StudentSearchOutputPort;
use App\Business\Services\UseLoggedInCompanyAccountTrait;
use App\Domain\Entities\Member;
use App\Domain\Entities\School;
use App\Utilities\Log;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class StudentSearchUseCase
 *
 * @package App\Business\UseCases\Client
 */
class StudentSearchUseCase implements StudentSearchInitializeInteractor,StudentSearchInteractor
{
    use UseLoggedInCompanyAccountTrait;
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 20;

    /**
     * @var BusinessTypeRepository
     */
    private $businessTypeRepository;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * StudentSearchUseCase constructor.
     *
     * @param BusinessTypeRepository $businessTypeRepository
     * @param CompanyAccountRepository $companyAccountRepository
     * @param PrefectureRepository $prefectureRepository
     * @param MemberRepository $memberRepository
     */
    public function __construct(
        BusinessTypeRepository $businessTypeRepository,
        CompanyAccountRepository $companyAccountRepository,
        PrefectureRepository $prefectureRepository,
        MemberRepository $memberRepository)
    {
        $this->businessTypeRepository = $businessTypeRepository;
        $this->companyAccountRepository = $companyAccountRepository;
        $this->prefectureRepository = $prefectureRepository;
        $this->memberRepository = $memberRepository;
    }
    /**
     * 初期表示
     *
     * @param StudentSearchInitializeInputPort $inputPort
     * @param StudentSearchInitializeOutputPort $outputPort
     */
    public function initialize(StudentSearchInitializeInputPort $inputPort, StudentSearchInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $reloadFlg = $inputPort->reloadFlg;
        $outputPort->reloadFlg = $reloadFlg;

        // 学部系統リスト
        $outputPort->facultyTypeList = School::FACULTY_TYPE_LIST;
        $outputPort->overseasFacultyTypeList = School::OVERSEAS_FACULTY_TYPE_LIST;

        // 業種リスト
        $businessTypes = $this->businessTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $businessTypeNameList = array_column($businessTypes, "name");
        $businessTypeIdList = array_column($businessTypes, "id");
        $outputPort->businessTypeList = array_combine($businessTypeIdList, $businessTypeNameList);

        // 都道府県リスト
        $prefectures = $this->prefectureRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $prefectureNameList = array_column($prefectures, "name");
        $prefectureIdList = array_column($prefectures, "id");
        $outputPort->prefectureList = array_combine($prefectureIdList, $prefectureNameList);

        // 卒業年月リスト
        $outputPort->yearList = School::getGraduationTwelveYearListYearAgo();
        $outputPort->monthList = School::getAllMonthList();
        $outputPort->overseasList = Member::CITIZENSHIP_OVERSEAS_LIST;

        //ログ出力
        Log::infoOut();
    }

    /**
     * 検索する
     *
     * @param StudentSearchInputPort $inputPort
     * @param StudentSearchOutputPort $outputPort
     */
    public function search(StudentSearchInputPort $inputPort, StudentSearchOutputPort $outputPort): void
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

        $members = $this->memberRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(MemberSearchCriteria::class, MemberSearchExpressionBuilder::class,
                $inputPort,
                [
                    "pager" => $pager,
                ]
            )
        );

        $outputPort->members = $members;
        $outputPort->loggedInAccount = $this->getLoggedInCompanyAccount($inputPort);
        //ログ出力
        Log::infoOut();
    }

    /**
     * 検索する
     *
     * @param StudentSearchInputPort $inputPort
     * @param StudentSearchOutputPort $outputPort
     */
    public function overseasSearch(StudentSearchInputPort $inputPort, StudentSearchOutputPort $outputPort): void
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

        $members = $this->memberRepository->findByOverSeasCriteria(
            CriteriaFactory::getInstance()->create(MemberSearchCriteria::class, MemberSearchExpressionBuilder::class,
                $inputPort,
                [
                    "pager" => $pager,
                ]
            )
        );

        $outputPort->members = $members;
        $outputPort->loggedInAccount = $this->getLoggedInCompanyAccount($inputPort);
        //ログ出力
        Log::infoOut();
    }

}
