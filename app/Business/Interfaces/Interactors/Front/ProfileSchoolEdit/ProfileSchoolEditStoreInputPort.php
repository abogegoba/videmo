<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfileSchoolEditStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit
 * @property string $schoolType 学校種別
 * @property string $name 学校名
 * @property string $departmentName 学部・学科名
 * @property string $facultyType 学部系統
 * @property string $graduationPeriodYear 卒業年
 * @property string $graduationPeriodMonth 卒業月
 * @property int $country 現住所 (都道府県)
 */
interface ProfileSchoolEditStoreInputPort extends UseLoggedInMemberInputPort
{
}
