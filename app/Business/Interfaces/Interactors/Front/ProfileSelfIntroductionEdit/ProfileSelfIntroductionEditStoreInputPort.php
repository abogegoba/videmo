<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfileSelfIntroductionEditStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit
 * @property array $selfIntroductions 自己紹介
 * @property string $selfIntroduction10Title 自由入力タイトル
 */
interface ProfileSelfIntroductionEditStoreInputPort extends UseLoggedInMemberInputPort
{
}