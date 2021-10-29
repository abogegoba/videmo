<?php

namespace App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList;

/**
 * Interface VideoInterviewCompanyListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList
 */
interface VideoInterviewCompanyListInitializeInteractor
{
    /**
     * @param VideoInterviewCompanyListInitializeInputPort $inputPort
     * @param VideoInterviewCompanyListInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewCompanyListInitializeInputPort $inputPort, VideoInterviewCompanyListInitializeOutputPort $outputPort): void;
}