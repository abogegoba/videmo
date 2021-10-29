<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewRoom;

/**
 * Interface VideoInterviewRoomInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberEntry
 */
interface VideoInterviewRoomInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param VideoInterviewRoomInitializeInputPort $inputPort
     * @param VideoInterviewRoomInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewRoomInitializeInputPort $inputPort, VideoInterviewRoomInitializeOutputPort $outputPort): void;
}