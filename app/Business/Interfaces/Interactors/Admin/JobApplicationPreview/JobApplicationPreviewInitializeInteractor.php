<?php

namespace App\Business\Interfaces\Interactors\Admin\JobApplicationPreview;

/**
 * Interface JobApplicationPreviewInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationPreview
 */
interface JobApplicationPreviewInitializeInteractor
{
    /**
     * プレビューを表示する
     *
     * @param JobApplicationPreviewInitializeInputPort $inputPort
     * @param JobApplicationPreviewInitializeOutputPort $outputPort
     */
    public function preview(JobApplicationPreviewInitializeInputPort $inputPort, JobApplicationPreviewInitializeOutputPort $outputPort): void;
}