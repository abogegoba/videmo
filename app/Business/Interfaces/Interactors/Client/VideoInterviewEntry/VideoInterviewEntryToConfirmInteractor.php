<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewEntry;

/**
 * Interface VideoInterviewEntryToConfirmInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewEntry
 */
interface VideoInterviewEntryToConfirmInteractor
{
    /**
     * 確認画面へ画面遷移
     *
     * @param VideoInterviewEntryToConfirmInputPort $inputPort
     * @param VideoInterviewEntryToConfirmOutputPort $outputPort
     */
    public function toConfirm(VideoInterviewEntryToConfirmInputPort $inputPort, VideoInterviewEntryToConfirmOutputPort $outputPort): void;
}