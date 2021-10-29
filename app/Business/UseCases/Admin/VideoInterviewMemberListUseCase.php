<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Criteria\VideoCallHistoryListSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\VideoInterviewMemberListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\VideoCallHistoryRepository;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList\VideoInterviewMemberListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList\VideoInterviewMemberListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList\VideoInterviewMemberListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList\VideoInterviewMemberListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList\VideoInterviewMemberListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList\VideoInterviewMemberListSearchOutputPort;
use App\Utilities\Log;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class VideoInterviewMemberListUseCase
 *
 * 会員別ビデオ通話一覧を一覧する
 *
 * @package App\Business\UseCases\Admin
 */
class VideoInterviewMemberListUseCase implements VideoInterviewMemberListSearchInteractor, VideoInterviewMemberListInitializeInteractor
{
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var VideoCallHistoryRepository
     */
    private $videoCallHistoryRepository;

    /**
     * VideoInterviewMemberListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param VideoCallHistoryRepository $videoCallHistoryRepository
     */
    public function __construct(
        MemberRepository $memberRepository,
        VideoCallHistoryRepository $videoCallHistoryRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->videoCallHistoryRepository = $videoCallHistoryRepository;
    }

    /**
     * 初期化する
     *
     * @param VideoInterviewMemberListInitializeInputPort $inputPort
     * @param VideoInterviewMemberListInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewMemberListInitializeInputPort $inputPort, VideoInterviewMemberListInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 会員IDから会員を取得
        $member = $this->memberRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->memberId
                ]
            )
        );

        // 会員ID
        $outputPort['memberId'] = $inputPort->memberId;

        // 会員名(会員かな名)を取得
        $memberName = $member->getLastName() . ' ' . $member->getFirstName();
        $memberNameKana = $member->getLastNameKana() . ' ' . $member->getFirstNameKana();
        if (!empty($memberNameKana)) {
            $memberName = $memberName . ' (' . $memberNameKana . ')';
        }
        $outputPort['memberName'] = $memberName;

        // 連絡先電話番号
        $outputPort['phoneNumber'] = $member->getPhoneNumber();

        // メールアドレス
        $userAccount = $member->getUserAccount();
        $outputPort['mailAddress'] = $userAccount->getMailAddress();

        // ログ出力
        Log::infoOut();
    }

    /**
     * 検索する
     *
     * @param VideoInterviewMemberListSearchInputPort $inputPort
     * @param VideoInterviewMemberListSearchOutputPort $outputPort
     */
    public function search(VideoInterviewMemberListSearchInputPort $inputPort, VideoInterviewMemberListSearchOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // Inputにページ指定が存在しない場合は新規で作成する
        $pager = $inputPort->pager;
        if (!isset($pager)) {
            $pager = new Class() extends Data implements Pager
            {
            };
        }

        // 1ページ最大件数を設定する
        $pager->limit = self::PAGE_LIMIT_COUNT;
        $outputPort->pager = $pager;
        $videoCallHistories = $this->videoCallHistoryRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(VideoCallHistoryListSearchCriteria::class, VideoInterviewMemberListSearchExpressionBuilder::class,
                $inputPort,
                [
                    "pager" => $pager
                ]
            )
        );
        $outputPort->videoCallHistories = $videoCallHistories;

        // ログ出力
        Log::infoOut();
    }
}