<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Adapters\Http\Requests\Request;
use App\Utilities\Log;

/**
 * Class MemberListSearchRequest
 *
 * @package App\Adapters\Http\Requests\Admin
 */
class MemberListSearchRequest extends Request
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

        $validators = [
            // 会員名
            'memberName' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // 会員名かな
            'memberNameKana' => ['nullable', 'max:255', 'regex:/^[ぁ-ゞ1234567890\-\^\\¥\[\]_\@:\/;\.,!"#\$%&\'\(\)0=~\|\{\}_`\*\?\+><１２３４５６７８９０−＾￥「」＿＠：・；。、！”＃＄％＆’（）０＝〜｜｛｝＿｀＊？＋＞＜ー\-―‐ 　]+$/u'],
            // 学校名
            'schoolName' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // 連絡先電話番号
            'phoneNumber' => ['nullable', 'max:15', 'regex:/^[0-9]+$/'],
            // 卒業年
            'graduationPeriodYear' => ['nullable', 'min:4','date_format:"Y"'],
            // 卒業月
            'graduationPeriodMonth' => ['nullable', 'date_format:"m"'],
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
            // 会員名
            'memberName.max' => trans('validation.max', ['name' => '会員名']),
            'memberName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '会員名']),
            // 会員名かな
            'memberNameKana.max' => trans('validation.max', ['name' => '会員名かな']),
            'memberNameKana.regex' => trans('validation.other.kana', ['name' => '会員名かな']),
            // 学校名
            'schoolName.max' => trans('validation.max', ['name' => '学校名']),
            'schoolName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '学校名']),
            // 連絡先電話番号
            'phoneNumber.max' => trans('validation.max', ['name' => '連絡先電話番号']),
            'phoneNumber.regex' => trans('validation.regex', ['name' => '連絡先電話番号']),
            // 卒業年
            'graduationPeriodYear.date_format' => trans('validation.date_format', ['name' => '卒業年']),
            'graduationPeriodYear.min' => trans('validation.min', ['name' => '卒業年']),
            // 卒業月
            'graduationPeriodMonth.date_format' => trans('validation.date_format', ['name' => '卒業月']),
        ];

        //ログ出力
        Log::infoOut();

        return $messages;
    }
}