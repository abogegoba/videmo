<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Adapters\Http\Requests\Request;
use App\Adapters\Http\Validation\Validator;
use App\Domain\Entities\InterViewAppointment;
use App\Utilities\Log;

/**
 * Class InterviewAppointmentListSearchRequest
 *
 * @package App\Adapters\Http\Requests\Admin
 */
class InterviewAppointmentListSearchRequest extends Request
{
    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        //ログ出力
        Log::infoIn();

        $interviewAppointmentStatusListKeys = array_keys(InterviewAppointment::STATUS_LIST);
        $stringInterviewAppointmentStatusListKeys = implode(',',$interviewAppointmentStatusListKeys);

        $validators = [
            // 会員名
            'memberName' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // 会員名かな
            'memberNameKana' => ['nullable', 'max:255', 'regex:/^[ぁ-ゞ1234567890\-\^\\¥\[\]_\@:\/;\.,!"#\$%&\'\(\)0=~\|\{\}_`\*\?\+><１２３４５６７８９０−＾￥「」＿＠：・；。、！”＃＄％＆’（）０＝〜｜｛｝＿｀＊？＋＞＜ー\-―‐ 　]+$/u'],
            // 会社名
            'companyName' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // 会社名かな
            'companyNameKana' => ['nullable', 'max:255', 'regex:/^[ぁ-ゞ1234567890\-\^\\¥\[\]_\@:\/;\.,!"#\$%&\'\(\)0=~\|\{\}_`\*\?\+><１２３４５６７８９０−＾￥「」＿＠：・；。、！”＃＄％＆’（）０＝〜｜｛｝＿｀＊？＋＞＜ー\-―‐ 　]+$/u'],
            // ステータス
            'interviewAppointmentStatus' => ['in:'. $stringInterviewAppointmentStatusListKeys],
            // 予約日期間開始日（From）
            'startDateOfAppointmentPeriod' => ['nullable', 'date_format:"Y/m/d"'],
            // 予約日期間終了日（To）
            'endDateOfAppointmentPeriod' => ['nullable', 'date_format:"Y/m/d"'],
        ];

        //ログ出力
        Log::infoOut();

        return $validators;
    }

    /**
     * 複合関連項目バリデーションチェック定義
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        //ログ出力
        Log::infoIn();

        // 予約日期間開始日（From）及び 予約日期間終了日（To）どちらも入力された場合はFrom〜Toの関連チェックを追加したバリデーションチェックを再定義
        $validator->sometimes('startDateOfAppointmentPeriod', ['nullable', 'date_format:"Y/m/d"', 'before_or_equal:endDateOfAppointmentPeriod'], function ($input) {
            return !empty($input->startDateOfAppointmentPeriod) && !empty($input->endDateOfAppointmentPeriod);
        });
        $validator->sometimes('endDateOfAppointmentPeriod', ['nullable', 'date_format:"Y/m/d"', 'after_or_equal:startDateOfAppointmentPeriod'], function ($input) {
            return !empty($input->startDateOfAppointmentPeriod) && !empty($input->endDateOfAppointmentPeriod);
        });
        //ログ出力
        Log::infoOut();
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
            // 会社名
            'companyName.max' => trans('validation.max', ['name' => '会社名']),
            'companyName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '会社名']),
            // 会社名かな
            'companyNameKana.max' => trans('validation.max', ['name' => '会社名かな']),
            'companyNameKana.regex' => trans('validation.other.kana', ['name' => '会社名かな']),
            // 会員名
            'memberName.max' => trans('validation.max', ['name' => '会員名']),
            'memberName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '会員名']),
            // 会員名かな
            'memberNameKana.max' => trans('validation.max', ['name' => '会員名かな']),
            'memberNameKana.regex' => trans('validation.other.kana', ['name' => '会員名かな']),
            // ステータス
            'interviewAppointmentStatus.in' => trans('validation.choice.in', ['name' => 'ステータス']),
            // 予約日期間開始日（From）
            'startDateOfAppointmentPeriod.date_format' => trans('validation.date_format', ['name' => '予約日期間開始日']),
            'startDateOfAppointmentPeriod.before_or_equal' => trans('validation.before_or_equal', ['name' => '予約日期間開始日', 'target' => '予約日期間終了日']),
            // 予約日期間終了日（To）
            'endDateOfAppointmentPeriod.date_format' => trans('validation.date_format', ['name' => '予約日期間終了日']),
            'endDateOfAppointmentPeriod.after_or_equal' => trans('validation.after_or_equal', ['name' => '予約日期間終了日', 'target' => '予約日期間開始日']),
        ];

        //ログ出力
        Log::infoOut();

        return $messages;
    }
}