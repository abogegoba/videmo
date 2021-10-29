<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit;

/**
 * Interface ProfilePhotoEditUpdateInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit
 */
interface ProfilePhotoEditUpdateInteractor
{
    /**
     *  登録変更する
     *
     * @param ProfilePhotoEditUpdateInputPort $inputPort
     * @param ProfilePhotoEditUpdateOutputPort $outputPort
     */
    public function update(ProfilePhotoEditUpdateInputPort $inputPort, ProfilePhotoEditUpdateOutputPort $outputPort): void;
}
