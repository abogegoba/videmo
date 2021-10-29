<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewRoom;

/**
 * Interface VideoInterviewRoomStartInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewRoom
 */
interface VideoInterviewRoomStartInteractor
{
    /**
     * ビデオを開始する
     *
     * @param VideoInterviewRoomStartInputPort $inputPort
     * @param VideoInterviewRoomStartOutputPort $outputPort
     */
    public function startVideo(VideoInterviewRoomStartInputPort $inputPort, VideoInterviewRoomStartOutputPort $outputPort): void;
}