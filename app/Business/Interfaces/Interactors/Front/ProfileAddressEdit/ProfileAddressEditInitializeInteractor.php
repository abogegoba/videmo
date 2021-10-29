<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileAddressEdit;

/**
 * Interface CompanyRecruitingEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileAddressEdit
 */
interface ProfileAddressEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfileAddressEditInitializeInputPort $inputPort
     * @param ProfileAddressEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileAddressEditInitializeInputPort $inputPort, ProfileAddressEditInitializeOutputPort $outputPort): void;
}