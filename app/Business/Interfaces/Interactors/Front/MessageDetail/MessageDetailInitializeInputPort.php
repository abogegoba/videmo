<?php

namespace App\Business\Interfaces\Interactors\Front\MessageDetail;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface MessageDetailInitializeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 *
 * @property int $companyUserAccountId 企業ユーザーアカウントID
 * @property int $loggedInMemberId ログイン会員ID
 * @property int $url URL
 */
interface MessageDetailInitializeInputPort extends UseLoggedInMemberInputPort
{
}