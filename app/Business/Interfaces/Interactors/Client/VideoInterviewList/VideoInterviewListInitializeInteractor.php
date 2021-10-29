<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewList;

/**
 * Interface VideoInterviewListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberEntry
 */
interface VideoInterviewListInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param VideoInterviewListInitializeInputPort $inputPort
     * @param VideoInterviewListInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewListInitializeInputPort $inputPort, VideoInterviewListInitializeOutputPort $outputPort): void;
}