<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit;

/**
 * Interface ProfileDesiredEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit
 */
interface ProfileDesiredEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfileDesiredEditInitializeInputPort $inputPort
     * @param ProfileDesiredEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileDesiredEditInitializeInputPort $inputPort, ProfileDesiredEditInitializeOutputPort $outputPort): void;
}