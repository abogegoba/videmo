<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList;

/**
 * Interface VideoInterviewMemberListSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList
 */
interface VideoInterviewMemberListSearchInteractor
{
    /**
     * 検索
     *
     * @param VideoInterviewMemberListSearchInputPort $inputPort
     * @param VideoInterviewMemberListSearchOutputPort $outputPort
     */
    public function search(VideoInterviewMemberListSearchInputPort $inputPort, VideoInterviewMemberListSearchOutputPort $outputPort): void;
}