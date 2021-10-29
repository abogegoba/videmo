<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList;

use App\Domain\Entities\VideoCallHistory;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface VideoInterviewMemberListSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList
 *
 * @property VideoCallHistory[] $videoCallHistories ビデオ通話履歴（検索結果）
 * @property Pager $pager ページャー
 */
interface VideoInterviewMemberListSearchOutputPort
{
}