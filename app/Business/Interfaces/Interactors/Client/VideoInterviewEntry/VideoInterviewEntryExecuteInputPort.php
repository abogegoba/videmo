<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewEntry;

use  App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;

/**
 * Interface VideoInterviewEntryExecuteInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewEntry
 *
 * @property int $memberUserAccountId 会員ユーザーアカウントID
 * @property int $loggedInCompanyAccountId ログイン企業会員アカウントID
 * @property int $date 予約日
 * @property int $time 開始時間
 * @property string $content 内容
 */
interface VideoInterviewEntryExecuteInputPort extends UseLoggedInCompanyAccountInputPort
{
}