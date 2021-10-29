<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewEntry;

use  App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;

/**
 * Interface VideoInterviewEntryConfirmInitializeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewEntry
 *
 * @property int $memberUserAccountId 会員ユーザーアカウントID
 * @property string $date 予約日
 * @property string $time 開始時間
 * @property string content 内容
 */
interface VideoInterviewEntryConfirmInitializeInputPort extends UseLoggedInCompanyAccountInputPort
{
}