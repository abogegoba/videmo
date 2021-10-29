<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm;

/**
 * Interface VideoInterviewCancelExecuteInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm
 */
interface VideoInterviewCancelExecuteInteractor
{
    /**
     * キャンセル実行
     *
     * @param VideoInterviewCancelExecuteInputPort $inputPort
     * @param VideoInterviewCancelExecuteOutputPort $outputPort
     */
    public function execute(VideoInterviewCancelExecuteInputPort $inputPort, VideoInterviewCancelExecuteOutputPort $outputPort): void;
}