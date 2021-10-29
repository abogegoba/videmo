<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList;

/**
 * Interface InterviewAppointmentListSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList
 */
interface InterviewAppointmentListSearchInteractor
{
    /**
     * 検索
     *
     * @param InterviewAppointmentListSearchInputPort $inputPort
     * @param InterviewAppointmentListSearchOutputPort $outputPort
     */
    public function search(InterviewAppointmentListSearchInputPort $inputPort, InterviewAppointmentListSearchOutputPort $outputPort): void;
}