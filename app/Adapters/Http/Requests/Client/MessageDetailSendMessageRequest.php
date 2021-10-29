<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class MessageDetailSendMessageRequest
 *
 * @package App\Adapters\Http\Requests\Front
 */
class MessageDetailSendMessageRequest extends Request
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
            // メッセージ
            'messageToSend' => ['required',  'max:400', 'halfwidth_kana_control'],
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
            // メッセージ
            'messageToSend.required' => trans('validation.required', ['name' => 'メッセージ']),
            'messageToSend.max' => trans('validation.max', ['name' => 'メッセージ']),
            'messageToSend.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'メッセージ']),
        ];
        //ログ出力
        Log::infoOut();
        return $messages;
    }
}