<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList;

use App\Domain\Entities\VideoCallHistory;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface VideoInterviewCompanyListSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList
 *
 * @property VideoCallHistory[] $videoCallHistories ビデオ通話履歴（検索結果）
 * @property Pager $pager ページャー
 */
interface VideoInterviewCompanyListSearchOutputPort
{
}