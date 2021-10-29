<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\SelfIntroductionRepository;
use App\Business\Interfaces\Interactors\Front\Profile\ProfileInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\Profile\ProfileInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Profile\ProfileInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\Profile\ProfilePreviewInputPort;
use App\Business\Interfaces\Interactors\Front\Profile\ProfilePreviewInteractor;
use App\Business\Interfaces\Interactors\Front\Profile\ProfilePreviewOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\SelfIntroduction;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class ProfileUseCase
 *
 * @package App\Business\UseCases\Front
 */
class ProfileUseCase implements ProfileInitializeInteractor, ProfilePreviewInteractor
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
     * @param ProfileInitializeInputPort $inputPort
     * @param ProfileInitializeOutputPort $outputPort
     */
    public function initialize(ProfileInitializeInputPort $inputPort, ProfileInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $outputPort->member = $member;
        $memberId = $member->getId();
        $selfIntroductions = [];
        for ($i = 1; $i <= 10; $i++) {
            $selfIntroduction = $this->getSelfIntroductionByDisplayNumberAndMemberId($i, $memberId);
            if ($i === 10) {
                $selfIntroduction10Title = '未登録（タイトル自由入力）';
                if (($selfIntroduction !== null) && $selfIntroduction->getTitle()) {
                    $selfIntroduction10Title = $selfIntroduction->getTitle();
                }
                $selfIntroductions[$i]['title'] = $selfIntroduction10Title;
            } else {
                $selfIntroductions[$i]['title'] = SelfIntroduction::SELF_TITLE_LIST[$i];
            }
            $selfIntroductions[$i]['content'] = ($selfIntroduction !== null) ? $selfIntroduction->getContent() : '未登録';
        }
        $outputPort->selfIntroductions = $selfIntroductions;
        $prVideos = [];
        foreach ($member->getPrVideos() as $prVideo) {
            $prVideos[] = [
                "title" => $prVideo->getTitle(),
                "url" => $prVideo->getFilePathForFrontShow(),
                "description" => $prVideo->getDescription()
            ];
        }
        $outputPort->prVideos = $prVideos;

        Log::infoOut();
    }

    /**
     * プレビュー画面を表示する
     *
     * @param ProfilePreviewInputPort $inputPort
     * @param ProfilePreviewOutputPort $outputPort
     */
    public function preview(ProfilePreviewInputPort $inputPort, ProfilePreviewOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();
        $member = $this->getLoggedInMember($inputPort);
        $outputPort->member = $member;

        $prVideos = [];
        foreach ($member->getPrVideos() as $prVideo) {
            $prVideos[] = [
                'prVideoPath' => $prVideo->getFilePathForFrontShow(),
                'title' => $prVideo->getTitle(),
                'description' => $prVideo->getDescription(),
            ];
        }
        $outputPort->prVideos = $prVideos;

        // ログ出力
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
