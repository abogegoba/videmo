<?php

namespace App\Business\Interfaces\Interactors\Front\VideoInterviewRoom;

/**
 * Interface VideoInterviewRoomInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
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