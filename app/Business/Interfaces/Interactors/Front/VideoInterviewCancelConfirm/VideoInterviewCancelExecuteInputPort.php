<?php

namespace App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface VideoInterviewCancelExecuteInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm
 *
 *
 * @property string $interviewAppointmentId 面接予約ID
 * @property string $loggedInMemberId ログイン会員アカウントID
 * @property string $cancelMessage キャンセルメッセージ
 */
interface VideoInterviewCancelExecuteInputPort  extends UseLoggedInMemberInputPort
{
}