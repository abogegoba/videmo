<?php

namespace App\Business\Interfaces\Interactors\Backend\JobShow;

/**
 * Interface JobShowInteractor
 *
 * @package App\Business\Interfaces\Interactors\Backend\JobShow
 */
interface JobShowInteractor
{
    /**
     * ジョブを参照する
     *
     * @param JobShowInputPort $inputPort
     * @param JobShowOutputPort $outputPort
     */
    public function show($inputPort, $outputPort): void;
}