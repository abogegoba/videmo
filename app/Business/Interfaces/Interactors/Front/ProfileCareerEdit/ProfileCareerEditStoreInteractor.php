<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileCareerEdit;

/**
 * Interface ProfileCareerEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileCareerEdit
 */
interface ProfileCareerEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfileCareerEditStoreInputPort $inputPort
     * @param ProfileCareerEditStoreOutputPort $outputPort
     */
    public function store(ProfileCareerEditStoreInputPort $inputPort, ProfileCareerEditStoreOutputPort $outputPort): void;
}