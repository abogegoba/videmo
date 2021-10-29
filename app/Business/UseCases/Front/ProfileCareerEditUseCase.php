<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CareerRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Interactors\Front\ProfileCareerEdit\ProfileCareerEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileCareerEdit\ProfileCareerEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileCareerEdit\ProfileCareerEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfileCareerEdit\ProfileCareerEditStoreInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileCareerEdit\ProfileCareerEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileCareerEdit\ProfileCareerEditStoreOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Business\Services\YearMonthTrait;
use App\Domain\Entities\Career;
use App\Utilities\Log;
use Carbon\Carbon;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class ProfileCareerEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfileCareerEditUseCase implements ProfileCareerEditInitializeInteractor, ProfileCareerEditStoreInteractor
{
    use UseLoggedInMemberTrait, YearMonthTrait;

    /**
     * @var CareerRepository
     */
    private $careerRepository;

    /**
     * MemberHistoryListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param CareerRepository $careerRepository
     */
    public function __construct(MemberRepository $memberRepository, CareerRepository $careerRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->careerRepository = $careerRepository;
    }

    /**
     * 初期表示
     *
     * @param ProfileCareerEditInitializeInputPort $inputPort
     * @param ProfileCareerEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileCareerEditInitializeInputPort $inputPort, ProfileCareerEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);

        $outputPort->careers = $member->getCareers();
        $outputPort->yearList = ['' => "選択してください"] + self::getGraduationYearListTenYearAgo();
        $outputPort->monthList = ['' => "選択してください"] + self::getAllMonthList();
        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録変更する
     *
     * @param ProfileCareerEditStoreInputPort $inputPort
     * @param ProfileCareerEditStoreOutputPort $outputPort
     */
    public function store(ProfileCareerEditStoreInputPort $inputPort, ProfileCareerEditStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $originalCareers = $member->getCareers();
        $beforeCareerDisplayNumberList = [];
        if ($originalCareers !== null) {
           foreach ($originalCareers as $originalCareer){
               $beforeCareerDisplayNumberList[] = $originalCareer->getDisplayNumber();
           }
        }

        $names = $inputPort->names;
        $memberId = $member->getId();
        $careers = [];
        foreach ($names as $key => $name) {
            $periodYear = $inputPort->periodYears[$key];
            $periodMonth = $inputPort->periodMonths[$key];
            // 経歴年月・経歴名が入力されている場合のみ
            if (!empty($name) && !empty($periodYear) && !empty($periodMonth)) {
                $career = $this->getCareerByDisplayNumberAndMemberId($key, $memberId);
                if ($career === null) {
                    $career = new Career();
                    $career->setDisplayNumber($key);
                }
                $career->setPeriod(new Carbon($periodYear . sprintf('%02d', $periodMonth) . '01'));
                $career->setName($name);
                $career->setMember($member);
                $careers[] = $career;
                unset($beforeCareerDisplayNumberList[array_search($key, $beforeCareerDisplayNumberList)]);
            }
        }

        // 不要になった経歴年月・経歴名があれば物理削除
        if (count($beforeCareerDisplayNumberList) > 0) {
            foreach ($beforeCareerDisplayNumberList as $beforeCareerDisplayNumber) {
                $deletedCareer = $this->getCareerByDisplayNumberAndMemberId($beforeCareerDisplayNumber, $memberId);
                $this->careerRepository->physicalDelete($deletedCareer);
                Log::infoOperationDeleteLog("", ["career" => (array)$deletedCareer], "");
            }
        }

        $member->setCareers($careers);
        $this->memberRepository->saveOrUpdate($member, true);
        //ログ出力
        Log::infoOut();
    }

    /**
     * 経歴を表示順とメンバーIDから取得
     *
     * @param int $displayNumber
     * @param int $memberId
     * @return Career|null
     */
    private function getCareerByDisplayNumberAndMemberId(int $displayNumber, int $memberId): ?Career
    {
        try {
            $career = $this->careerRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, [
                    "displayNumber" => $displayNumber,
                    "member" => $memberId,
                ])
            );
        } catch (ObjectNotFoundException $e) {
            $career = null;
        }
        return $career;
    }
}