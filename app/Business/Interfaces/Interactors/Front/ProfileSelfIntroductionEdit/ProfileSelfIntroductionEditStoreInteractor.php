<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit;

/**
 * Interface ProfileSelfIntroductionEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit
 */
interface ProfileSelfIntroductionEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfileSelfIntroductionEditStoreInputPort $inputPort
     * @param ProfileSelfIntroductionEditStoreOutputPort $outputPort
     */
    public function store(ProfileSelfIntroductionEditStoreInputPort $inputPort, ProfileSelfIntroductionEditStoreOutputPort $outputPort): void;
}