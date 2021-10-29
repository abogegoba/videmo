<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewEntry;

/**
 * Interface VideoInterviewEntryInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewEntry
 */
interface VideoInterviewEntryInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param VideoInterviewEntryInitializeInputPort $inputPort
     * @param VideoInterviewEntryInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewEntryInitializeInputPort $inputPort, VideoInterviewEntryInitializeOutputPort $outputPort): void;
}