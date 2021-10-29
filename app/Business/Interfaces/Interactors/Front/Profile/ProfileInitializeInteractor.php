<?php

namespace App\Business\Interfaces\Interactors\Front\Profile;

/**
 * Interface ProfileInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\Profile
 */
interface ProfileInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfileInitializeInputPort $inputPort
     * @param ProfileInitializeOutputPort $outputPort
     */
    public function initialize(ProfileInitializeInputPort $inputPort, ProfileInitializeOutputPort $outputPort): void;
}