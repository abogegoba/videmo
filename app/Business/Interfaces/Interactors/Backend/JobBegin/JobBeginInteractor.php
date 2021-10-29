<?php

namespace App\Business\Interfaces\Interactors\Backend\JobBegin;

/**
 * Interface JobBeginInteractor
 *
 * @package App\Business\Interfaces\Interactors\Backend\JobBegin
 */
interface JobBeginInteractor
{
    /**
     * ジョブを開始する
     *
     * @param JobBeginInputPort $inputPort
     */
    public function begin($inputPort): void;
}