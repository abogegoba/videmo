<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit;

use App\Domain\Entities\Certification;

/**
 * Interface ProfileLanguageEditInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit
 * @property int $toeicScore TOEIC点数
 * @property int $toeflScore TOEFL点数
 * @property array $certificationList 保有資格・検定等
 * @property Certification[] $certifications 保有資格・検定等
 */
interface ProfileLanguageEditInitializeOutputPort
{
}