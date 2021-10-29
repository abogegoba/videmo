<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class LikeMemberCreateStoreRequest
 *
 * @package App\Adapters\Http\Requests\Client
 */
class LikeMemberCreateStoreRequest extends Request
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
            // 求人タイトル
            //'company_id' => ['required'],
            // 募集職種
            //'memeber_id' => ['required'],
            // ステータス
            //'like_status' => ['required', "in:10,20"],
        ];

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
            // 会社ID
            'company_id.required' => trans('validation.required', ['name' => '会社ID']),
            'company_id.integer' => trans('validation.integer', ['name' => '会社ID']),
            // メンバーID
            'company_id.required' => trans('validation.required', ['name' => 'メンバーID']),
            'company_id.integer' => trans('validation.integer', ['name' => 'メンバーID']),
            // ステータス
            'like_status.required' => trans('validation.required', ['name' => 'ステータス']),
            'like_status.in' => trans('validation.in', ['name' => 'ステータス']),
        ];

        Log::infoOut();

        return $messages;
    }
}
