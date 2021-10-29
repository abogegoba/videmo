<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList;

/**
 * Interface VideoInterviewMemberListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList
 */
interface VideoInterviewMemberListInitializeInteractor
{
    /**
     * @param VideoInterviewMemberListInitializeInputPort $inputPort
     * @param VideoInterviewMemberListInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewMemberListInitializeInputPort $inputPort, VideoInterviewMemberListInitializeOutputPort $outputPort): void;
}