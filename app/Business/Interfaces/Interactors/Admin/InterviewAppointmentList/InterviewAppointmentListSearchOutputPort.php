<?php

namespace App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList;

use ReLab\Commons\Interfaces\Pager;

/**
 * Interface InterviewAppointmentListSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList
 *
 * @property InterviewAppointment[]|Data $interviewAppointments 予約（検索結果）
 * @property Pager $pager ページャー
 */
interface InterviewAppointmentListSearchOutputPort
{
}