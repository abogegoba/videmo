<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit;

/**
 * Interface ProfilePersonalEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit
 */
interface ProfilePersonalEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfilePersonalEditInitializeInputPort $inputPort
     * @param ProfilePersonalEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfilePersonalEditInitializeInputPort $inputPort, ProfilePersonalEditInitializeOutputPort $outputPort): void;
}