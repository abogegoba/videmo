<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\BusinessTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\JobTypeSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit\ProfileDesiredEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit\ProfileDesiredEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit\ProfileDesiredEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit\ProfileDesiredEditStoreInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit\ProfileDesiredEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit\ProfileDesiredEditStoreOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\BusinessType;
use App\Domain\Entities\JobType;
use App\Domain\Entities\Prefecture;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class ProfileDesiredEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfileDesiredEditUseCase implements ProfileDesiredEditInitializeInteractor, ProfileDesiredEditStoreInteractor
{
    use UseLoggedInMemberTrait;

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
     * ProfileDesiredEditUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param BusinessTypeRepository $businessTypeRepository
     * @param JobTypeRepository $jobTypeRepository
     * @param PrefectureRepository $prefectureRepository
     */
    public function __construct(MemberRepository $memberRepository, BusinessTypeRepository $businessTypeRepository, JobTypeRepository $jobTypeRepository, PrefectureRepository $prefectureRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->businessTypeRepository = $businessTypeRepository;
        $this->jobTypeRepository = $jobTypeRepository;
        $this->prefectureRepository = $prefectureRepository;
    }

    /**
     * 初期表示
     *
     * @param ProfileDesiredEditInitializeInputPort $inputPort
     * @param ProfileDesiredEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileDesiredEditInitializeInputPort $inputPort, ProfileDesiredEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        // 業種リスト
        $businessTypes = $this->businessTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $businessTypeNameList = array_column($businessTypes, "name");
        $businessTypeIdList = array_column($businessTypes, "id");
        $outputPort->businessTypeList = array_combine($businessTypeIdList, $businessTypeNameList);

        // 職種リスト
        $jobTypes = $this->jobTypeRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(JobTypeSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $jobTypeNameList = array_column($jobTypes, "name");
        $jobTypeIdList = array_column($jobTypes, "id");
        $outputPort->jobTypeList = array_combine($jobTypeIdList, $jobTypeNameList);

        // 都道府県リスト
        $prefectures = $this->prefectureRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class), ["id", "name"]
        );
        $prefectureNameList = array_column($prefectures, "name");
        $prefectureIdList = array_column($prefectures, "id");
        $outputPort->prefectureList = array_combine($prefectureIdList, $prefectureNameList);

        $member = $this->getLoggedInMember($inputPort);
        //　志望業種
        $aspirationBusinessTypes = $member->getAspirationBusinessTypes();
        if (!empty($aspirationBusinessTypes)) {
            $outputPort->industry1 = (!empty($aspirationBusinessTypes[0]) ? $aspirationBusinessTypes[0]->getId() : null);
            $outputPort->industry2 = (!empty($aspirationBusinessTypes[1]) ? $aspirationBusinessTypes[1]->getId() : null);
            $outputPort->industry3 = (!empty($aspirationBusinessTypes[2]) ? $aspirationBusinessTypes[2]->getId() : null);
        }

        //　志望職種
        $aspirationJobTypes = $member->getAspirationJobTypes();
        if (!empty($aspirationJobTypes)) {
            $outputPort->jobType1 = (!empty($aspirationJobTypes[0]) ? $aspirationJobTypes[0]->getId() : null);
            $outputPort->jobType2 = (!empty($aspirationJobTypes[1]) ? $aspirationJobTypes[1]->getId() : null);
            $outputPort->jobType3 = (!empty($aspirationJobTypes[2]) ? $aspirationJobTypes[2]->getId() : null);
        }

        //　志望勤務地
        $aspirationPrefectures = $member->getAspirationPrefectures();
        if (!empty($aspirationPrefectures)) {
            $outputPort->location1 = (!empty($aspirationPrefectures[0]) ? $aspirationPrefectures[0]->getId() : null);
            $outputPort->location2 = (!empty($aspirationPrefectures[1]) ? $aspirationPrefectures[1]->getId() : null);
            $outputPort->location3 = (!empty($aspirationPrefectures[2]) ? $aspirationPrefectures[2]->getId() : null);
        }
        $outputPort->intern = $member->getInternNeeded();
        $outputPort->recruitInfo = $member->getRecruitInfoNeeded();
        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録変更する
     *
     * @param ProfileDesiredEditStoreInputPort $inputPort
     * @param ProfileDesiredEditStoreOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function store(ProfileDesiredEditStoreInputPort $inputPort, ProfileDesiredEditStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $member = $this->getLoggedInMember($inputPort);

        // 志望職種存在チェック
        $industry1 = $this->getBusinessTypeById($inputPort->industry1);
        $industry2 = $this->getBusinessTypeById($inputPort->industry2);
        $industry3 = $this->getBusinessTypeById($inputPort->industry3);
        $member->setAspirationBusinessTypes([$industry1, $industry2, $industry3]);

        $jobType1 = $this->getJobTypeById($inputPort->jobType1);
        $jobType2 = $this->getJobTypeById($inputPort->jobType2);
        $jobType3 = $this->getJobTypeById($inputPort->jobType3);
        $member->setAspirationJobTypes([$jobType1, $jobType2, $jobType3]);

        // 志望勤務地存在チェック
        $location1 = $this->getPrefectureById($inputPort->location1);
        $location2 = $this->getPrefectureById($inputPort->location2);
        $location3 = $this->getPrefectureById($inputPort->location3);
        $member->setAspirationPrefectures([$location1, $location2, $location3]);

        // インターン希望
        $member->setInternNeeded($inputPort->intern === '1');
        $member->setRecruitInfoNeeded($inputPort->recruitInfo === '1');
        // 変更
        $this->memberRepository->saveOrUpdate($member, true);

        //ログ出力
        Log::infoOut();
    }

    /**
     * 業種を取得
     *
     * @param int|null $industryId
     * @return BusinessType|null
     * @throws FatalBusinessException
     */
    private function getBusinessTypeById(?int $industryId): ?BusinessType
    {
        $businessType = null;
        if (!empty($industryId)) {
            try {
                $businessType = $this->businessTypeRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(BusinessTypeSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $industryId])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $businessType;
    }

    /**
     * 職種を取得
     *
     * @param int|null $jobTypeId
     * @return JobType|null
     * @throws FatalBusinessException
     */
    private function getJobTypeById(?int $jobTypeId): ?JobType
    {
        $jobType = null;
        if (!empty($jobTypeId)) {
            try {
                $jobType = $this->jobTypeRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(JobTypeSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $jobTypeId])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $jobType;
    }

    /**
     * 都道府県を取得
     *
     * @param int|null $prefectureId
     * @return Prefecture|null
     * @throws FatalBusinessException
     */
    private function getPrefectureById(?int $prefectureId): ?Prefecture
    {
        $prefecture = null;
        if (!empty($prefectureId)) {
            try {
                $prefecture = $this->prefectureRepository->findOneByCriteria(
                    CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class, ["id" => $prefectureId])
                );
            } catch (ObjectNotFoundException $e) {
                throw new FatalBusinessException("select_target_not_found");
            }
        }
        return $prefecture;
    }
}
