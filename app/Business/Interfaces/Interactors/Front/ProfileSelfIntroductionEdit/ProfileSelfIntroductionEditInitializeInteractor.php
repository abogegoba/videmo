<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit;

/**
 * Interface ProfileSelfIntroductionEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit
 */
interface ProfileSelfIntroductionEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ProfileSelfIntroductionEditInitializeInputPort $inputPort
     * @param ProfileSelfIntroductionEditInitializeOutputPort $outputPort
     */
    public function initialize(ProfileSelfIntroductionEditInitializeInputPort $inputPort, ProfileSelfIntroductionEditInitializeOutputPort $outputPort): void;
}