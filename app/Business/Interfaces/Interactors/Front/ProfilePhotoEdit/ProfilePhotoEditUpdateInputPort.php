<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfilePhotoEditStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit
 *
 * @property string $idPhotoName 証明写真名
 * @property string $idPhotoPath 証明写真パス
 * @property string $privatePhotoName プライベート写真名
 * @property string $privatePhotoPath プライベート写真パス
 * @property string $hashTag ハッシュタグ
 * @property int $hashTagColor ハッシュタグカラー
 */
interface ProfilePhotoEditUpdateInputPort extends UseLoggedInMemberInputPort
{
}