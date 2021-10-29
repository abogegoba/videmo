<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberCreate;

/**
 * Interface MemberCreateStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberCreate
 *
 * @property string $lastName 氏名(姓)
 * @property string $firstName 氏名(名)
 * @property string $lastNameKana 氏名かな(せい)
 * @property string $firstNameKana 氏名かな(めい)
 * @property string $birthday 生年月日
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
 * @property string $idPhotoName 証明写真名
 * @property string $idPhotoPath 証明写真パス
 * @property string $privatePhotoName プライベート写真名
 * @property string $privatePhotoPath プライベート写真パス
 * @property string $hashTag ハッシュタグ
 * @property string $hashTagColor ハッシュタグカラー
 * @property string $mailAddress メールアドレス
 * @property string $password パスワード
 * @property string[] $prVideoNames PR動画名
 * @property string[] $prVideoPaths PR動画パス
 * @property string[] $prVideoTitles PR動画タイトル
 * @property string[] $prVideoDescriptions PR動画説明
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
 * @property string[] $certificationList 保有資格・検定など
 * @property int[] $careerPeriodYears 経歴年
 * @property int[] $careerPeriodMonths 経歴月
 * @property int[] $careerNames 経歴
 * @property string $managementMemo 管理メモ
 * @property int $status ステーテス
 * @property int $sendMail メール送信
 */
interface MemberCreateStoreInputPort
{
}
