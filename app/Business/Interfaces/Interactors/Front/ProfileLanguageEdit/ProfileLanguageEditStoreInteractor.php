<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit;

/**
 * Interface ProfileLanguageEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit
 */
interface ProfileLanguageEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfileLanguageEditStoreInputPort $inputPort
     * @param ProfileLanguageEditStoreOutputPort $outputPort
     */
    public function store(ProfileLanguageEditStoreInputPort $inputPort, ProfileLanguageEditStoreOutputPort $outputPort): void;
}