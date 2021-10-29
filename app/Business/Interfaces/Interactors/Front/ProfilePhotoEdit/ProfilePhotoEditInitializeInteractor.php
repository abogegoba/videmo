<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit;

/**
 * Interface ProfilePhotoEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit
 */
interface ProfilePhotoEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfilePhotoEditInitializeInputPort $inputPort
     * @param ProfilePhotoEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfilePhotoEditInitializeInputPort $inputPort, ProfilePhotoEditInitializeOutputPort $outputPort): void;
}
