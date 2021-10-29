<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class ClientLoginRequest
 *
 * @package App\Adapters\Http\Requests\Front
 */
class ClientLoginRequest extends Request
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
            // メールアドレス
            'mailAddress' => ['required'],
            // パスワード
            'password' => ['required'],
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
            // メールアドレス
            'mailAddress.required' => trans('validation.required', ['name' => 'メールアドレス']),
            // パスワード
            'password.required' => trans('validation.required', ['name' => 'パスワード']),
        ];

        //ログ出力
        Log::infoOut();

        return $messages;
    }
}