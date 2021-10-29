<?php

namespace App\Business\Interfaces\Interactors\Client\MessageDetail;

use  App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;

/**
 * Interface MessageDetailInitializeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberEntry
 *
 * @property int $memberUserAccountId 会員ユーザーアカウントID
 * @property int $loggedInCompanyAccountId ログイン企業会員ID
 */
interface MessageDetailInitializeInputPort  extends UseLoggedInCompanyAccountInputPort
{
}