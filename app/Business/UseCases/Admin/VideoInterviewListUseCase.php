<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\VideoCallHistoryListSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\VideoInterviewListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\VideoCallHistoryRepository;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewList\VideoInterviewListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewList\VideoInterviewListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewList\VideoInterviewListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewList\VideoInterviewListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewList\VideoInterviewListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewList\VideoInterviewListSearchOutputPort;
use App\Utilities\Log;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class VideoInterviewListUseCase
 *
 * ビデオ通話履歴を一覧する
 *
 * @package App\Business\UseCases\Admin
 */
class VideoInterviewListUseCase implements VideoInterviewListSearchInteractor, VideoInterviewListInitializeInteractor
{
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var VideoCallHistoryRepository
     */
    private $videoCallHistoryRepository;

    /**
     * VideoInterviewListUseCase constructor.
     *
     * @param VideoCallHistoryRepository $videoCallHistoryRepository
     */
    public function __construct(
        VideoCallHistoryRepository $videoCallHistoryRepository
    ) {
        $this->videoCallHistoryRepository = $videoCallHistoryRepository;
    }

    /**
     * 初期化する
     *
     * @param VideoInterviewListInitializeInputPort $inputPort
     * @param VideoInterviewListInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewListInitializeInputPort $inputPort, VideoInterviewListInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // ログ出力
        Log::infoOut();
    }

    /**
     * 検索する
     *
     * @param VideoInterviewListSearchInputPort $inputPort
     * @param VideoInterviewListSearchOutputPort $outputPort
     */
    public function search(VideoInterviewListSearchInputPort $inputPort, VideoInterviewListSearchOutputPort $outputPort): void
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
            CriteriaFactory::getInstance()->create(VideoCallHistoryListSearchCriteria::class, VideoInterviewListSearchExpressionBuilder::class,
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