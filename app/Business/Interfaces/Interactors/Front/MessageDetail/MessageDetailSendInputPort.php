<?php

namespace App\Business\Interfaces\Interactors\Front\MessageDetail;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface MessageDetailSendInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 *
 * @property string $messageToSend 送信用メッセージ
 * @property string $companyUserAccountId 企業ユーザーアカウントID
 * @property string $loggedInMemberId ログイン会員アカウントID
 */
interface MessageDetailSendInputPort extends UseLoggedInMemberInputPort
{
}