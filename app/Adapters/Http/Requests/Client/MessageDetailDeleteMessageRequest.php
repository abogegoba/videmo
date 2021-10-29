<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class MessageDetailDeleteMessageRequest
 *
 * @package App\Adapters\Http\Requests\Front
 */
class MessageDetailDeleteMessageRequest extends Request
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
            'id' => ['required'],
            'status' => ['required'],
            'sending_user_account_id' => ['required'],
            'receiving_user_account_id' => ['required'],
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
            'id.required' => trans('validation.required', ['name' => 'ID']),
            'status.required' => trans('validation.required', ['name' => 'Status']),
            'sending_user_account_id.required' => trans('validation.required', ['name' => 'Sending user account id']),
            'receiving_user_account_id.required' => trans('validation.required', ['name' => 'Receiving user account id']),
        ];
        //ログ出力
        Log::infoOut();
        return $messages;
    }
}
