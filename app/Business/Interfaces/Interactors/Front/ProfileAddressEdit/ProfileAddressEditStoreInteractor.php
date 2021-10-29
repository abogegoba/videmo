<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileAddressEdit;

/**
 * Interface CompanyRecruitingEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileAddressEdit
 */
interface ProfileAddressEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfileAddressEditStoreInputPort $inputPort
     * @param ProfileAddressEditStoreOutputPort $outputPort
     */
    public function store(ProfileAddressEditStoreInputPort $inputPort, ProfileAddressEditStoreOutputPort $outputPort): void;
}