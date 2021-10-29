<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate;

/**
 * Interface InterviewAppointmentCreateInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate
 */
interface InterviewAppointmentCreateInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param InterviewAppointmentCreateInitializeInputPort $inputPort
     * @param InterviewAppointmentCreateInitializeOutputPort $outputPort
     */
    public function initialize(InterviewAppointmentCreateInitializeInputPort $inputPort, InterviewAppointmentCreateInitializeOutputPort $outputPort): void;
}