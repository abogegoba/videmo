<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCancel;

/**
 * Interface InterviewAppointmentCancelRevokeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCancel
 */
interface InterviewAppointmentCancelRevokeInteractor
{
    /**
     * 取り消す
     *
     * @param InterviewAppointmentCancelRevokeInputPort $inputPort
     * @param InterviewAppointmentCancelRevokeOutputPort $outputPort
     */
    public function revoke(InterviewAppointmentCancelRevokeInputPort $inputPort, InterviewAppointmentCancelRevokeOutputPort $outputPort): void;
}