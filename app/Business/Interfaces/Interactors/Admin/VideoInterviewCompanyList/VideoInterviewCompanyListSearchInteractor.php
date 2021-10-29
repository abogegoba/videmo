<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList;

/**
 * Interface VideoInterviewCompanyListSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList
 */
interface VideoInterviewCompanyListSearchInteractor
{
    /**
     * 検索
     *
     * @param VideoInterviewCompanyListSearchInputPort $inputPort
     * @param VideoInterviewCompanyListSearchOutputPort $outputPort
     */
    public function search(VideoInterviewCompanyListSearchInputPort $inputPort, VideoInterviewCompanyListSearchOutputPort $outputPort): void;
}