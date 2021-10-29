<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Adapters\Http\Requests\Request;
use App\Domain\Entities\Message;
use App\Utilities\Log;

class AdminMessageListSearchRequest extends Request
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

        $messageStatusListKeys = array_keys(Message::STATUS_LIST);
        $stringMessageStatusListKeys = implode(',',$messageStatusListKeys);

        $validators = [
            // 会社名
            'companyName' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // 会員名
            'name' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // ステータス
            'statusList' => ['nullable', 'in:'. $stringMessageStatusListKeys],
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
            // 会員名
            'name.max' => trans('validation.max', ['name' => '会員名']),
            'name.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '会員名']),
            // ステータス
            'statusList.in' => trans('validation.choice.in', ['name' => 'ステータス']),
        ];

        // ログ出力
        Log::infoOut();
        return $messages;
    }

}