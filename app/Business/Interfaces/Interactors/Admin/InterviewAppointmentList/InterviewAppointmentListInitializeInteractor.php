<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList;

/**
 * Interface InterviewAppointmentListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList
 */
interface InterviewAppointmentListInitializeInteractor
{
    /**
     * @param InterviewAppointmentListInitializeInputPort $inputPort
     * @param InterviewAppointmentListInitializeOutputPort $outputPort
     */
    public function initialize(InterviewAppointmentListInitializeInputPort $inputPort, InterviewAppointmentListInitializeOutputPort $outputPort): void;
}