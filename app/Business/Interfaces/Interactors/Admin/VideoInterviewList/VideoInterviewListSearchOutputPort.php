<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewList;

use App\Domain\Entities\VideoCallHistory;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface VideoInterviewListSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewList
 *
 * @property VideoCallHistory[] $videoCallHistories ビデオ通話履歴（検索結果）
 * @property Pager $pager ページャー
 */
interface VideoInterviewListSearchOutputPort
{
}