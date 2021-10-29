<?php

namespace App\Business\Interfaces\Interactors\Backend\JobFailed;

/**
 * Interface JobFailedInteractor
 *
 * @package App\Business\Interfaces\Interactors\Backend\JobFailed
 */
interface JobFailedInteractor
{
    /**
     * ジョブを失敗する
     *
     * @param JobFailedInputPort $inputPort
     */
    public function failed($inputPort): void;
}