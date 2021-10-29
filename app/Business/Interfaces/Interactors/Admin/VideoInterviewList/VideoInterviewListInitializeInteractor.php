<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewList;

/**
 * Interface VideoInterviewListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewList
 */
interface VideoInterviewListInitializeInteractor
{
    /**
     * @param VideoInterviewListInitializeInputPort $inputPort
     * @param VideoInterviewListInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewListInitializeInputPort $inputPort, VideoInterviewListInitializeOutputPort $outputPort): void;
}