<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Adapters\Http\Requests\Request;
use App\Utilities\Log;

class VideoInterviewListSearchRequest extends Request
{
    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        // ログ出力
        Log::infoIn();

        $validators = [
            // 会社名
            'companyName' => ['nullable', 'max:255',  'halfwidth_kana_control'],
            // 会社名かな
            'companyNameKana' => ['nullable', 'max:255',  'regex:/^[ぁ-ゞ1234567890\-\^\\¥\[\]_\@:\/;\.,!"#\$%&\'\(\)0=~\|\{\}_`\*\?\+><１２３４５６７８９０−＾￥「」＿＠：・；。、！”＃＄％＆’（）０＝〜｜｛｝＿｀＊？＋＞＜ー\-―‐ 　]+$/u'],
            // 会員名
            'memberName' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // 会員名かな
            'memberNameKana' => ['nullable', 'max:255', 'regex:/^[ぁ-ゞ1234567890\-\^\\¥\[\]_\@:\/;\.,!"#\$%&\'\(\)0=~\|\{\}_`\*\?\+><１２３４５６７８９０−＾￥「」＿＠：・；。、！”＃＄％＆’（）０＝〜｜｛｝＿｀＊？＋＞＜ー\-―‐ 　]+$/u'],
        ];

        // ログ出力
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
        // ログ出力
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
        ];

        // ログ出力
        Log::infoOut();
        return $messages;
    }

}