<?php

namespace App\Business\Interfaces\Interactors\Admin\JobApplicationEdit;

/**
 * Interface JobApplicationEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationEdit
 */
interface JobApplicationEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param JobApplicationEditInitializeInputPort $inputPort
     * @param JobApplicationEditInitializeOutputPort $outputPort
     */
    public function initialize(JobApplicationEditInitializeInputPort $inputPort, JobApplicationEditInitializeOutputPort $outputPort): void;
}