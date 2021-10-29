<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfileIdAndPassEditStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit
 * @property string $mailAddress メールアドレス
 * @property string $password パスワード
 * @property string $confirmPassword パスワード確認用
 */
interface ProfileIdAndPassEditStoreInputPort extends UseLoggedInMemberInputPort
{
}