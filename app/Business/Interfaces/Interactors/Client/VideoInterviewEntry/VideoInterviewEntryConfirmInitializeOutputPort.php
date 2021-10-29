<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewEntry;

/**
 * Interface VideoInterviewEntryConfirmInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewEntry
 *
 * @property string $privateImage プライベート写真
 * @property string $idImage 証明写真
 * @property string $memberName 会員氏名
 * @property string $schoolName 学校名
 * @property string $departmentName 学部
 * @property int $age 年齢
 * @property string $graduationPeriod 卒業予定年
 * @property string $hashTagName ハッシュタグ
 * @property string $hashTagColor ハッシュタグカラー
 * @property string $EntryDate 予約日
 * @property string $EntryTime 開始時間
 * @property string $date 予約日
 * @property string $time 開始時間
 * @property string $content 内容
 * @property string $videoInterviewCancelUrl 面接キャンセルURL
 * @property int $memberUserAccountId 会員ユーザーアカウントID
 * @property string $videoInterviewReviseUrl 予約登録修正URL
 */
interface VideoInterviewEntryConfirmInitializeOutputPort
{
}