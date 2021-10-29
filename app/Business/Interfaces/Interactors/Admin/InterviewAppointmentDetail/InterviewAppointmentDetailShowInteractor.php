<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentDetail;

/**
 * Interface InterviewAppointmentDetailShowInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentDetail
 */
interface InterviewAppointmentDetailShowInteractor
{
    /**
     * 初期化する
     *
     * @param InterviewAppointmentDetailShowInputPort $inputPort
     * @param InterviewAppointmentDetailShowOutputPort $outputPort
     */
    public function show(InterviewAppointmentDetailShowInputPort $inputPort, InterviewAppointmentDetailShowOutputPort $outputPort): void;
}