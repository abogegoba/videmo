<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate;

/**
 * Interface InterviewAppointmentCreateStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate
 *
 * @property int companyId 対象企業ID
 * @property int memberId 対象会員ID
 * @property string $appointmentDate 予約日時（日付）
 * @property string $appointmentTime 予約日時（時間）
 * @property string $content 内容
 * @property int $sendMailToMember 会員へメール送信
 * @property int $sendMailToCompany 担当者へメール送信
 */
interface InterviewAppointmentCreateStoreInputPort
{
}