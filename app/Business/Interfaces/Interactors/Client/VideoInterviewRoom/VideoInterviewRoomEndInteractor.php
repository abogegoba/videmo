<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewRoom;

/**
 * Interface VideoInterviewRoomEndInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewRoom
 */
interface VideoInterviewRoomEndInteractor
{
    /**
     * ビデオを終了する
     *
     * @param VideoInterviewRoomEndInputPort $inputPort
     * @param VideoInterviewRoomEndOutputPort $outputPort
     */
    public function endVideo(VideoInterviewRoomEndInputPort $inputPort, VideoInterviewRoomEndOutputPort $outputPort): void;
}