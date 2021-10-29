<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePREdit;

/**
 * Interface ProfilePREditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePREdit
 */
interface ProfilePREditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfilePREditInitializeInputPort $inputPort
     * @param ProfilePREditInitializeOutputPort $outputPort
     */
    public function initialize(ProfilePREditInitializeInputPort $inputPort, ProfilePREditInitializeOutputPort $outputPort): void;
}