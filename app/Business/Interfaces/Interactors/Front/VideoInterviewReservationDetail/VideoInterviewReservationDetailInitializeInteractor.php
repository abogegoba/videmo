<?php

namespace App\Business\Interfaces\Interactors\Front\VideoInterviewReservationDetail;

/**
 * Interface VideoInterviewReservationDetailInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\VideoInterviewReservationDetail
 */
interface VideoInterviewReservationDetailInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param VideoInterviewReservationDetailInitializeInputPort $inputPort
     * @param VideoInterviewReservationDetailInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewReservationDetailInitializeInputPort $inputPort, VideoInterviewReservationDetailInitializeOutputPort $outputPort): void;
}