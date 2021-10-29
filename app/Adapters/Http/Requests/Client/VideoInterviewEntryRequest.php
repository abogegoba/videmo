<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class VideoInterviewEntryRequest
 *
 * @package App\Adapters\Http\Requests\Client
 */
class VideoInterviewEntryRequest extends Request
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
            // 面接日
            'date' => ['date_format:"Y-m-d"'],
            // 開始時間
            'time' => ['date_format:"H:i"'],
            // 内容
            'content' =>  ["max:400", 'halfwidth_kana_control'],
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
//            // 予約日
            'date.required' => trans('validation.required', ['name' => '面接日']),
            'date.date_format' => trans('validation.date_format', ['name' => '面接日']),
//            // 開始時間
            'time.required' => trans('validation.required', ['name' => '開始時間']),
            'time.date_format' => trans('validation.date_format', ['name' => '開始時間']),
            // 内容
            'content.max' => trans('validation.max', ['name' => '内容']),
            'content.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '内容']),
        ];

        Log::infoOut();

        return $messages;
    }
}