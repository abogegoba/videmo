<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit;

/**
 * Interface ProfileSchoolEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit
 */
interface ProfileSchoolEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfileSchoolEditInitializeInputPort $inputPort
     * @param ProfileSchoolEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileSchoolEditInitializeInputPort $inputPort, ProfileSchoolEditInitializeOutputPort $outputPort): void;
}