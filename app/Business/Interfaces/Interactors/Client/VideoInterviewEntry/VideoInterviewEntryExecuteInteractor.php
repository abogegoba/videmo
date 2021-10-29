<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewEntry;

/**
 * Interface VideoInterviewEntryExecuteInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewEntry
 */
interface VideoInterviewEntryExecuteInteractor
{
    /**
     * 予約登録実行
     *
     * @param VideoInterviewEntryExecuteInputPort $inputPort
     * @param VideoInterviewEntryExecuteOutputPort $outputPort
     */
    public function execute(VideoInterviewEntryExecuteInputPort $inputPort, VideoInterviewEntryExecuteOutputPort $outputPort): void;
}