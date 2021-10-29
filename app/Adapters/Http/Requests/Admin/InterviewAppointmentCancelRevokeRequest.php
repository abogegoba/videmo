<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class InterviewAppointmentCancelRevokeRequest
 *
 * @package App\Adapters\Http\Requests\Admin
 */
class InterviewAppointmentCancelRevokeRequest extends Request
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
            // キャンセルメッセージ
            'cancelMessage' => ['nullable', 'max:400', 'halfwidth_kana_control'],
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
            // キャンセルメッセージ
            'cancelMessage.max' => trans('validation.max', ['name' => '内容']),
            'cancelMessage.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '内容']),
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