<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryConfirmInitializeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 * @property string $lastName 氏名(姓)
 * @property string $firstName 氏名(名)
 * @property string $lastNameKana 氏名かな(せい)
 * @property string $firstNameKana 氏名かな(めい)
 * @property string $englishName english name
 * @property string $birthday 生年月日
 * @property string $zipCode 郵便番号
 * @property int $country 都道府県
 * @property int $prefecture 都道府県
 * @property string $city 市区町村
 * @property string $blockNumber 番地・建物名・部屋番号など
 * @property string $phoneNumber 電話番号
 * @property int $schoolType 学校種別
 * @property string $name 学校名
 * @property string $departmentName 学部・学科名
 * @property int $facultyType 学部系統
 * @property int $graduationPeriodYear 卒業年
 * @property int $graduationPeriodMonth 卒業月
 * @property int $industry1 志望業種1
 * @property int $industry2 志望業種2
 * @property int $industry3 志望業種3
 * @property int $location1 志望勤務地1
 * @property int $location2 志望勤務地2
 * @property int $location3 志望勤務地3
 * @property bool $intern インターン希望
 * @property bool $recruitInfo 募集情報が必要です
 * @property string $mailAddress メールアドレス
 * @property string $password パスワード
 * @property string $confirmPassword パスワード確認用
 */
interface MemberEntryConfirmInitializeInputPort
{
}
