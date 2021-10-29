<?php


namespace App\Business\Interfaces\Interactors\Admin\JobApplicationList;

/**
 * Interface JobApplicationListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationList
 */
interface JobApplicationListInitializeInteractor
{
    /**
     * 初期表示
     *
     * @param JobApplicationListInitializeInputPort $inputPort
     * @param JobApplicationListInitializeOutputPort $outputPort
     */
    public function initialize(JobApplicationListInitializeInputPort $inputPort, JobApplicationListInitializeOutputPort $outputPort): void;
}