<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit;

/**
 * Interface ProfilePersonalEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit
 */
interface ProfilePersonalEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfilePersonalEditStoreInputPort $inputPort
     * @param ProfilePersonalEditStoreOutputPort $outputPort
     */
    public function store(ProfilePersonalEditStoreInputPort $inputPort, ProfilePersonalEditStoreOutputPort $outputPort): void;
}