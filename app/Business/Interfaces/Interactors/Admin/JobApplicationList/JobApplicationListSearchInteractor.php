<?php


namespace App\Business\Interfaces\Interactors\Admin\JobApplicationList;

/**
 * Interface JobApplicationListSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationList
 */
interface JobApplicationListSearchInteractor
{
    /**
     * 検索する
     *
     * @param JobApplicationListSearchInputPort $inputPort
     * @param JobApplicationListSearchOutputPort $outputPort
     */
    public function search(JobApplicationListSearchInputPort $inputPort, JobApplicationListSearchOutputPort $outputPort): void;
}