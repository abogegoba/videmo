<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewList;

/**
 * Interface VideoInterviewListSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewList
 */
interface VideoInterviewListSearchInteractor
{
    /**
     * 検索
     *
     * @param VideoInterviewListSearchInputPort $inputPort
     * @param VideoInterviewListSearchOutputPort $outputPort
     */
    public function search(VideoInterviewListSearchInputPort $inputPort, VideoInterviewListSearchOutputPort $outputPort): void;
}