<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCancel;

/**
 * Interface InterviewAppointmentCancelRevokeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCancel
 *
 * @property int $interviewAppointmentId 面接予約ID
 * @property string $cancelMessage キャンセルメッセージ
 * @property int $sendMailToMember 会員へメール送信
 * @property int $sendMailToCompany 担当者へメール送信
 */
interface InterviewAppointmentCancelRevokeInputPort
{
}