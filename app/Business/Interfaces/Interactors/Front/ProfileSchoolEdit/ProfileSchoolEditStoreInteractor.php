<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit;

/**
 * Interface ProfileSchoolEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit
 */
interface ProfileSchoolEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfileSchoolEditStoreInputPort $inputPort
     * @param ProfileSchoolEditStoreOutputPort $outputPort
     */
    public function store(ProfileSchoolEditStoreInputPort $inputPort, ProfileSchoolEditStoreOutputPort $outputPort): void;
}