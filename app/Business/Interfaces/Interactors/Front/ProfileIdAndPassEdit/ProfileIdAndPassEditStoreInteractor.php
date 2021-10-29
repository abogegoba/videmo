<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit;

/**
 * Interface ProfileIdAndPassEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit
 */
interface ProfileIdAndPassEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfileIdAndPassEditStoreInputPort $inputPort
     * @param ProfileIdAndPassEditStoreOutputPort $outputPort
     */
    public function store(ProfileIdAndPassEditStoreInputPort $inputPort, ProfileIdAndPassEditStoreOutputPort $outputPort): void;
}