<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit;

/**
 * Interface ProfileIdAndPassEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit
 */
interface ProfileIdAndPassEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfileIdAndPassEditInitializeInputPort $inputPort
     * @param ProfileIdAndPassEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileIdAndPassEditInitializeInputPort $inputPort, ProfileIdAndPassEditInitializeOutputPort $outputPort): void;
}