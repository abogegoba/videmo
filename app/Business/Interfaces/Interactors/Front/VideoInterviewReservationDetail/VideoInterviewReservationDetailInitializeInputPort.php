<?php

namespace App\Business\Interfaces\Interactors\Front\VideoInterviewReservationDetail;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface VideoInterviewReservationDetailInitializeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\VideoInterviewReservationDetail
 *
 * @property int $interviewAppointmentId 面接予約ID
 * @property int $loggedInMemberId ログイン会員ID
 */
interface VideoInterviewReservationDetailInitializeInputPort extends UseLoggedInMemberInputPort
{
}