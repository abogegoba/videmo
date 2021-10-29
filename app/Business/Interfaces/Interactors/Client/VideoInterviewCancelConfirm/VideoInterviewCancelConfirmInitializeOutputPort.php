<?php

namespace App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm;

/**
 * Interface VideoInterviewCancelConfirmInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm
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
 * @property string $appointmentDate 予約日
 * @property string $appointmentTime 開始時間
 * @property string $content 内容
 * @property string $videoInterviewCancelUrl 面接キャンセルURL
 * @property string $videoInterviewReservationDetailUrl 面接予約詳細URL
 * @property int interviewAppointmentId 面接予約ID
 */
interface VideoInterviewCancelConfirmInitializeOutputPort
{
}