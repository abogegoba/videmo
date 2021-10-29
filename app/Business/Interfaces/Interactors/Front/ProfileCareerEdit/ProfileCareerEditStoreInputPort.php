<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileCareerEdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfileCareerEditStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileCareerEdit
 * @property array $periodYears 経歴年リスト
 * @property array $periodMonths 経歴月リスト
 * @property array $names 経歴リスト
 */
interface ProfileCareerEditStoreInputPort extends UseLoggedInMemberInputPort
{
}