<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberEdit;

use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateInitializeOutputPort;
use App\Domain\Entities\Career;
use App\Domain\Entities\Certification;
use ReLab\Commons\Wrappers\Data;

/**
 * Interface MemberEditInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberEdit
 *
 * @property int $memberId 会員ID
 * @property string $lastName 氏名(姓)
 * @property string $firstName 氏名(名)
 * @property string $lastNameKana 氏名かな(せい)
 * @property string $firstNameKana 氏名かな(めい)
 * @property string $englishName english name
 * @property string $birthday 生年月日
 * @property int $country 現住所 (都道府県)
 * @property string $zipCode 郵便番号
 * @property int $prefecture 都道府県
 * @property string $city 市区町村
 * @property string $blockNumber 番地・建物名・部屋番号など
 * @property string $phoneNumber 電話番号
 * @property int $schoolType 学校種別
 * @property int $schoolName 学校名
 * @property int $departmentName 学部・学科名
 * @property int $facultyType 学部系統
 * @property int $graduationPeriodYear 卒業年
 * @property int $graduationPeriodMonth 卒業月
 * @property array $idPhoto 証明写真
 * @property array $privatePhoto プライベート写真
 * @property string $hashTag ハッシュタグ
 * @property string $hashTagColor ハッシュタグカラー
 * @property string $mailAddress メールアドレス
 * @property string $password パスワード
 * @property array $prVideos PR動画
 * @property string $introduction 自己PR文
 * @property int $affiliationExperience 体育会系所属経験
 * @property int $instagramFollowerNumber インスタフォロワー数
 * @property string[] $selfIntroductions 自己紹介文
 * @property string $selfIntroduction10Title 自己紹介（自由入力タイトル）
 * @property int $industry1 志望業種1
 * @property int $industry2 志望業種2
 * @property int $industry3 志望業種3
 * @property int $jobType1 志望職種1
 * @property int $jobType2 志望職種2
 * @property int $jobType3 志望職種3
 * @property int $location1 志望勤務地1
 * @property int $location2 志望勤務地2
 * @property int $location3 志望勤務地3
 * @property int $internNeeded インターン希望
 * @property int $recruitInfoNeeded 募集情報が必要です
 * @property int $toeicScore TOEIC
 * @property int $toeflScore TOEFL
 * @property Data[]|Certification[] $certifications 保有資格・検定など
 * @property Data[]|Career[] $careers 経歴
 * @property string $managementMemo 管理メモ
 * @property int $status ステーテス
 */
interface MemberEditInitializeOutputPort extends MemberCreateInitializeOutputPort
{
}
