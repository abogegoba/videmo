<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfilePersonalEditStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit
 * @property string $lastName 氏名(姓)
 * @property string $firstName 氏名(名)
 * @property string $lastNameKana 氏名かな(せい)
 * @property string $firstNameKana 氏名かな(めい)
 * @property string $birthday 生年月日
 * @property int $country 現住所 (都道府県)
 * @property string $englishName english name
 */
interface ProfilePersonalEditStoreInputPort extends UseLoggedInMemberInputPort
{
}
