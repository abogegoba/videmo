<?php

namespace App\Business\Interfaces\Interactors\Client\MessageDetail;

use  App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;

/**
 * Interface MessageDetailSendInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberEntry
 *
 * @property string $messageToSend 送信用メッセージ
 * @property string $memberUserAccountId 会員ユーザーアカウントID
 * @property string $loggedInCompanyAccountId ログイン企業会員アカウントID
 */
interface MessageDetailSendInputPort extends UseLoggedInCompanyAccountInputPort
{
}