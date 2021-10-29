<?php

namespace App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm;

/**
 * Interface VideoInterviewCancelConfirmInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm
 */
interface VideoInterviewCancelConfirmInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param VideoInterviewCancelConfirmInitializeInputPort $inputPort
     * @param VideoInterviewCancelConfirmInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewCancelConfirmInitializeInputPort $inputPort, VideoInterviewCancelConfirmInitializeOutputPort $outputPort): void;
}