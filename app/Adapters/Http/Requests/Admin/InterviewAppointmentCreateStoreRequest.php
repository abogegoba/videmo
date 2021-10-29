<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class InterviewAppointmentCreateStoreRequest
 *
 * @package App\Adapters\Http\Requests\Admin
 */
class InterviewAppointmentCreateStoreRequest extends Request
{
    /**
     * バリデーションチェック定義
     *
     * @return array
     */
    public function rules()
    {
        //ログ出力
        Log::infoIn();

        $validators = [
            // 対象企業
            'companyId' => ['required', 'integer'],
            // 対象会員
            'memberId' => ['required', 'integer'],
            // 予約日時（日付）
            'appointmentDate' => ['required', 'date_format:"Y/m/d"'],
            // 予約日時（時間）
            'appointmentTime' => ['required','regex:/^([0-1]?[0-9]|[2][0-3]):(00|15|30|45)$/u'],
            // 内容
            'content' => ['nullable', 'max:400', 'halfwidth_kana_control'],
            // 会員へメール送信
            'sendMailToMember' => ['required', 'integer', 'in:0,1'],
            // 担当者へメール送信
            'sendMailToCompany' => ['required', 'integer', 'in:0,1'],
        ];

        //ログ出力
        Log::infoOut();

        return $validators;
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        //ログ出力
        Log::infoIn();

        $messages = [
            // 対象企業
            'companyId.required' => trans('validation.choice.required', ['name' => '対象企業']),
            'companyId.integer' => trans('validation.choice.integer', ['name' => '対象企業']),
            // 対象会員
            'memberId.required' => trans('validation.choice.required', ['name' => '対象会員']),
            'memberId.integer' => trans('validation.choice.integer', ['name' => '対象会員']),
            // 予約日時（日付）
            'appointmentDate.required' => trans('validation.required', ['name' => '予約日時（日付）']),
            'appointmentDate.date_format' => trans('validation.date_format', ['name' => '予約日時（日付）']),
            // 予約日時（時間）
            'appointmentTime.required' => trans('validation.required', ['name' => '予約日時（時間）']),
            'appointmentTime.regex' => trans('validation.other.fifteenMinutes', ['name' => '予約日時（時間）']),
            // 管理用メモ
            'content.max' => trans('validation.max', ['name' => '内容']),
            'content.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '内容']),
            // 会員へメール送信
            'sendMailToMember.required' => trans('validation.choice.required', ['name' => '会員へメール送信']),
            'sendMailToMember.integer' => trans('validation.choice.integer', ['name' => '会員へメール送信']),
            'sendMailToMember.in' => trans('validation.choice.in', ['name' => '会員へメール送信']),
            // 担当者へメール送信
            'sendMailToCompany.required' => trans('validation.choice.required', ['name' => '担当者へメール送信']),
            'sendMailToCompany.integer' => trans('validation.choice.integer', ['name' => '担当者へメール送信']),
            'sendMailToCompany.in' => trans('validation.choice.in', ['name' => '担当者へメール送信']),
        ];

        //ログ出力
        Log::infoOut();

        return $messages;
    }
}