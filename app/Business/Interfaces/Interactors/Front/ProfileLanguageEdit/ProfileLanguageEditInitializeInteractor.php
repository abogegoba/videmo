<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit;

/**
 * Interface ProfileLanguageEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit
 */
interface ProfileLanguageEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfileLanguageEditInitializeInputPort $inputPort
     * @param ProfileLanguageEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileLanguageEditInitializeInputPort $inputPort, ProfileLanguageEditInitializeOutputPort $outputPort): void;
}