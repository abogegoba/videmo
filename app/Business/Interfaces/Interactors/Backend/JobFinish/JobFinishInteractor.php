<?php

namespace App\Business\Interfaces\Interactors\Backend\JobFinish;

/**
 * Interface JobFinishInteractor
 *
 * @package App\Business\Interfaces\Interactors\Backend\JobFinish
 */
interface JobFinishInteractor
{
    /**
     * ジョブを終了する
     *
     * @param JobFinishInputPort $inputPort
     */
    public function finish($inputPort): void;
}