<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfileDesiredEditStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit
 * @property int $industry1 志望業種1
 * @property int $industry2 志望業種2
 * @property int $industry3 志望業種3
 * @property int $jobType1 志望職種1
 * @property int $jobType2 志望職種2
 * @property int $jobType3 志望職種3
 * @property int $location1 志望勤務地1
 * @property int $location2 志望勤務地2
 * @property int $location3 志望勤務地3
 * @property int $intern インターン希望
 * @property int $recruitInfo 募集情報が必要です
 */
interface ProfileDesiredEditStoreInputPort extends UseLoggedInMemberInputPort
{
}
