<?php

namespace App\Business\Interfaces\Interactors\Front\Profile;

/**
 * Interface ProfilePreviewInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\Profile
 */
interface ProfilePreviewInteractor
{
    /**
     * プレビュー画面を表示する
     *
     * @param ProfilePreviewInputPort $inputPort
     * @param ProfilePreviewOutputPort $outputPort
     */
    public function preview(ProfilePreviewInputPort $inputPort, ProfilePreviewOutputPort $outputPort): void;
}