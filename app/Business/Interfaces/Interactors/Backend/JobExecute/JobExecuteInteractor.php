<?php

namespace App\Business\Interfaces\Interactors\Backend\JobExecute;

/**
 * Interface JobExecuteInteractor
 *
 * @package App\Business\Interfaces\Interactors\Backend\JobExecute
 */
interface JobExecuteInteractor
{
    /**
     * ジョブを実行する
     *
     * @param JobExecuteInputPort $inputPort
     * @param JobExecuteOutputPort $outputPort
     */
    public function execute($inputPort, $outputPort): void;
}