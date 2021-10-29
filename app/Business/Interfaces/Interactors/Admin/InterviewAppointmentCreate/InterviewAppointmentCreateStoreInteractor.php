<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate;

/**
 * Interface InterviewAppointmentCreateStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate
 */
interface InterviewAppointmentCreateStoreInteractor
{
    /**
     * 登録する
     *
     * @param InterviewAppointmentCreateStoreInputPort $inputPort
     * @param InterviewAppointmentCreateStoreOutputPort $outputPort
     */
    public function store(InterviewAppointmentCreateStoreInputPort $inputPort, InterviewAppointmentCreateStoreOutputPort $outputPort): void;
}