<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfileLanguageEditStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit
 * @property int $toeicScore TOEIC点数
 * @property int $toeflScore TOEFL点数
 * @property array $certificationList 保有資格・検定等
 */
interface ProfileLanguageEditStoreInputPort extends UseLoggedInMemberInputPort
{
}