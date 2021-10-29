<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewEntry;

use  App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;

/**
 * Interface VideoInterviewEntryInitializeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewEntry
 *
 * @property int $memberUserAccountId 会員ユーザーアカウント
 * @property int $loggedInCompanyAccountId ログイン企業会員ID
 */
interface VideoInterviewEntryInitializeInputPort extends UseLoggedInCompanyAccountInputPort
{
}