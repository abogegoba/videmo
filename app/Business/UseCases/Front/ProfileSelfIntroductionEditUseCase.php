<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\SelfIntroductionRepository;
use App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit\ProfileSelfIntroductionEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit\ProfileSelfIntroductionEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit\ProfileSelfIntroductionEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit\ProfileSelfIntroductionEditStoreInputPort;
use App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit\ProfileSelfIntroductionEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit\ProfileSelfIntroductionEditStoreOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\SelfIntroduction;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class ProfileSelfIntroductionEditUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfileSelfIntroductionEditUseCase implements ProfileSelfIntroductionEditInitializeInteractor, ProfileSelfIntroductionEditStoreInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var SelfIntroductionRepository
     */
    private $selfIntroductionRepository;

    /**
     * MemberHistoryListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param SelfIntroductionRepository $selfIntroductionRepository
     */
    public function __construct(MemberRepository $memberRepository, SelfIntroductionRepository $selfIntroductionRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->selfIntroductionRepository = $selfIntroductionRepository;
    }

    /**
     * 初期表示
     *
     * @param ProfileSelfIntroductionEditInitializeInputPort $inputPort
     * @param ProfileSelfIntroductionEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileSelfIntroductionEditInitializeInputPort $inputPort, ProfileSelfIntroductionEditInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $memberId = $member->getId();

        $selfIntroductions = [];
        $selfIntroduction10Title = null;
        for ($i = 1; $i <= 10; $i++) {
            $selfIntroduction = $this->getSelfIntroductionByDisplayNumberAndMemberId($i, $memberId);
            if ($i === 10 && ($selfIntroduction !== null)) {
                $selfIntroduction10Title = $selfIntroduction->getTitle();
                $selfIntroductions[$i]['title'] = $selfIntroduction10Title;
            } else {
                $selfIntroductions[$i]['title'] = SelfIntroduction::SELF_TITLE_LIST[$i];
            }
            $selfIntroductions[$i]['content'] = ($selfIntroduction !== null) ? $selfIntroduction->getContent() : '';
        }
        $outputPort->selfIntroductions = $selfIntroductions;
        $outputPort->selfIntroduction10Title = $selfIntroduction10Title;

        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録変更する
     *
     * @param ProfileSelfIntroductionEditStoreInputPort $inputPort
     * @param ProfileSelfIntroductionEditStoreOutputPort $outputPort
     */
    public function store(ProfileSelfIntroductionEditStoreInputPort $inputPort, ProfileSelfIntroductionEditStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();
        $inputtedSelfIntroductions = $inputPort->selfIntroductions;

        $member = $this->getLoggedInMember($inputPort);
        $memberId = $member->getId();
        $titleList = SelfIntroduction::SELF_TITLE_LIST;
        $titleList[SelfIntroduction::SELF_DISPLAY_NUMBER_10] = $inputPort->selfIntroduction10Title;

        $selfIntroductions = [];
        foreach ($inputtedSelfIntroductions as $displayNumber => $inputtedSelfIntroduction) {
            $selfIntroduction = $this->getSelfIntroductionByDisplayNumberAndMemberId($displayNumber, $memberId);
            if (!empty($inputtedSelfIntroduction)) {
                if ($selfIntroduction === null) {
                    $selfIntroduction = new SelfIntroduction();
                    $selfIntroduction->setMember($member);
                    $selfIntroduction->setDisplayNumber($displayNumber);
                }
                $selfIntroduction->setTitle($titleList[$displayNumber]);
                $selfIntroduction->setContent($inputtedSelfIntroduction);
                $selfIntroductions[] = $selfIntroduction;
            } elseif ($selfIntroduction !== null) {
                // データに存在するが、入力値としてない場合は物理削除
                $this->selfIntroductionRepository->physicalDelete($selfIntroduction);
                Log::infoOperationDeleteLog("", ["selfIntroductions" => (array)$selfIntroduction], "");
            }
        }

        $member->setAspirationSelfIntroductions($selfIntroductions);

        $this->memberRepository->saveOrUpdate($member, true);

        // 操作ログ
        Log::infoOperationCreateLog("", ["selfIntroductions" => (array)$selfIntroductions], "");

        //ログ出力
        Log::infoOut();
    }

    /**
     * 自己紹介を表示順とメンバーIDから取得
     *
     * @param int $displayNumber
     * @param int $memberId
     * @return SelfIntroduction|null
     */
    private function getSelfIntroductionByDisplayNumberAndMemberId(int $displayNumber, int $memberId): ?SelfIntroduction
    {
        try {
            $selfIntroduction = $this->selfIntroductionRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class, [
                    "displayNumber" => $displayNumber,
                    "member" => $memberId,
                ])
            );
        } catch (ObjectNotFoundException $e) {
            $selfIntroduction = null;
        }
        return $selfIntroduction;
    }
}