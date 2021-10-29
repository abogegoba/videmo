<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\SchoolRepository;
use App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit\ProfileSchoolEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit\ProfileSchoolEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit\ProfileSchoolEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit\ProfileSchoolEditStoreInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit\ProfileSchoolEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit\ProfileSchoolEditStoreOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Business\Services\YearMonthTrait;
use App\Domain\Entities\School;
use App\Utilities\Log;
use Carbon\Carbon;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Wrappers\Data;

/**
 * Class ProfileSchoolEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfileSchoolEditUseCase implements ProfileSchoolEditInitializeInteractor, ProfileSchoolEditStoreInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var SchoolRepository
     */
    private $schoolRepository;

    /**
     * MemberHistoryListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param SchoolRepository $schoolRepository
     */
    public function __construct(MemberRepository $memberRepository,SchoolRepository $schoolRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->schoolRepository = $schoolRepository;
    }

    /**
     * 初期表示
     *
     * @param ProfileSchoolEditInitializeInputPort $inputPort
     * @param ProfileSchoolEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileSchoolEditInitializeInputPort $inputPort, ProfileSchoolEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $school = $member->getOldSchool();
        $outputPort->country = $member->getCountry();
        $outputPort->schoolType = $school->getSchoolType();
        $outputPort->name = $school->getName();
        $outputPort->departmentName = $school->getDepartmentName();
        $outputPort->facultyType = $school->getFacultyType();
        $graduationPeriod = $school->getGraduationPeriod();
        $outputPort->graduationPeriodYear = $graduationPeriod->format('Y');
        $outputPort->graduationPeriodMonth = $graduationPeriod->format('n');
        if($member->getCountry() == 1){
            $outputPort->facultyTypeList = School::FACULTY_TYPE_LIST;
        }else{
            $outputPort->facultyTypeList = School::OVERSEAS_FACULTY_TYPE_LIST;
        }
        $outputPort->schoolTypeList = School::SCHOOL_TYPE_LIST;
        $outputPort->yearList = School::getGraduationTwelveYearListYearAgo();
        $outputPort->monthList = School::getAllMonthList();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録変更する
     *
     * @param ProfileSchoolEditStoreInputPort $inputPort
     * @param ProfileSchoolEditStoreOutputPort $outputPort
     * @throws BusinessException
     */
    public function store(ProfileSchoolEditStoreInputPort $inputPort, ProfileSchoolEditStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);

        // 出身校
        $school = $member->getOldSchool();
        Data::mappingToObject($inputPort, $school);

        // 卒業年月
        $graduationPeriodYear = $inputPort->graduationPeriodYear;
        $graduationPeriodMonth = $inputPort->graduationPeriodMonth;

        // 卒業年度から、登録可能な卒業年月かを確認する
        $graduationPeriod = new Carbon($graduationPeriodYear . sprintf('%02d', $graduationPeriodMonth) . '01');
        $graduationTwelveYearListYearAgo = YearMonthTrait::getGraduationTwelveYearListYearAgo();
        $allMonthList = YearMonthTrait::getAllMonthList();
        if (!array_key_exists($graduationPeriodYear, $graduationTwelveYearListYearAgo) || !array_key_exists($graduationPeriodMonth, $allMonthList)) {
            // 現在の年を基準に2年前から10年先以外の場合
            throw new BusinessException('cant_store_graduation_period');
        }

        $school->setGraduationPeriod($graduationPeriod);
        $this->schoolRepository->saveOrUpdate($school, true);

        // 操作ログ
        Log::infoOperationUpdateLog("", ["school" => (array)$school], '');

        //ログ出力
        Log::infoOut();
    }
}
