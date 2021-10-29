<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileCareerEdit;

/**
 * Interface ProfileCareerEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileCareerEdit
 */
interface ProfileCareerEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfileCareerEditInitializeInputPort $inputPort
     * @param ProfileCareerEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileCareerEditInitializeInputPort $inputPort, ProfileCareerEditInitializeOutputPort $outputPort): void;
}