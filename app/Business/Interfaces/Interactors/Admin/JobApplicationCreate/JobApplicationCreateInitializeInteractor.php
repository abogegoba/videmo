<?php

namespace App\Business\Interfaces\Interactors\Admin\JobApplicationCreate;

/**
 * Interface JobApplicationCreateInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationCreate
 */
interface JobApplicationCreateInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param JobApplicationCreateInitializeInputPort $inputPort
     * @param JobApplicationCreateInitializeOutputPort $outputPort
     */
    public function initialize(JobApplicationCreateInitializeInputPort $inputPort, JobApplicationCreateInitializeOutputPort $outputPort): void;
}