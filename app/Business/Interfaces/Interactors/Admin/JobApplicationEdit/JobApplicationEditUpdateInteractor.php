<?php

namespace App\Business\Interfaces\Interactors\Admin\JobApplicationEdit;

/**
 * Interface JobApplicationEditUpdateInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationEdit
 */
interface JobApplicationEditUpdateInteractor
{
    /**
     * 変更する
     *
     * @param JobApplicationEditUpdateInputPort $inputPort
     * @param JobApplicationEditUpdateOutputPort $outputPort
     */
    public function update(JobApplicationEditUpdateInputPort $inputPort, JobApplicationEditUpdateOutputPort $outputPort): void;
}