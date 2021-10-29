<?php

namespace App\Business\Interfaces\Interactors\Admin\JobApplicationCreate;

/**
 * Interface JobApplicationCreateStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationCreate
 */
interface JobApplicationCreateStoreInteractor
{
    /**
     * 登録する
     *
     * @param JobApplicationCreateStoreInputPort $inputPort
     * @param JobApplicationCreateStoreOutputPort $outputPort
     */
    public function store(JobApplicationCreateStoreInputPort $inputPort, JobApplicationCreateStoreOutputPort $outputPort): void;
}