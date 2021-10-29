<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewEntry;

/**
 * Interface VideoInterviewEntryConfirmInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterview
 */
interface VideoInterviewEntryConfirmInitializeInteractor
{
    /**
     * 確認画面初期表示
     *
     * @param VideoInterviewEntryConfirmInitializeInputPort $inputPort
     * @param VideoInterviewEntryConfirmInitializeOutputPort $outputPort
     */
    public function confirmInitialize(VideoInterviewEntryConfirmInitializeInputPort $inputPort, VideoInterviewEntryConfirmInitializeOutputPort $outputPort): void;
}