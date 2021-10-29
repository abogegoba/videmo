<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePREdit;

use  App\Business\Interfaces\Interactors\Front\Common\UseLoggedInMemberInputPort;

/**
 * Interface ProfilePREditUpdateInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePREdit
 *
 * @property string[] $prVideoNames PR動画名
 * @property string[] $prVideoPaths PR動画パス
 * @property string[] $prVideoTitles PR動画タイトル
 * @property string[] $prVideoDescriptions PR動画説明文
 * @property int[] $prVideoTypes PR動画タイプ
 * @property string $introduction
 * @property int $affiliationExperience
 * @property int $instagramFollowerNumber
 * @property int $country 現住所 (都道府県)
 */
interface ProfilePREditUpdateInputPort extends UseLoggedInMemberInputPort
{
}
