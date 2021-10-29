<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm;

use  App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;

/**
 * Interface VideoInterviewCancelExecuteInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm
 *
 * @property string $interviewAppointmentId 面接予約ID
 * @property string $loggedInCompanyAccountId ログイン企業会員アカウントID
 * @property string $cancelMessage キャンセルメッセージ
 */
interface VideoInterviewCancelExecuteInputPort extends UseLoggedInCompanyAccountInputPort
{
}