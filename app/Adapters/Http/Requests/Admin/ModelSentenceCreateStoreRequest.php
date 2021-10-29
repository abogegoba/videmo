<?php


namespace App\Adapters\Http\Requests\Admin;


use App\Adapters\Http\Requests\Request;
use App\Utilities\Log;

class ModelSentenceCreateStoreRequest extends Request
{
    /**
     * バリデーションチェック定義
     *
     * @return array
     */
    public function rules()
    {
        // ログ出力
        Log::infoIn();

        $validators = [
            'modelSentenceType' => ['required','integer','max:20'],
            'name' => ['required', 'max:32'],
            'content' => ['required', 'max:400'],
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

        $message = [
            // 例文種別
            'modelSentenceType.required' => trans('validation.required', ['name' => '例文種別']),
            'modelSentenceType.integer' => trans('validation.integer', ['name' => '例文種別']),
            'modelSentenceType.max' => trans('validation.max', ['name' => '例文種別']),
            // 例文名
            'name.required' => trans('validation.required', ['name' => '例文名']),
            'name.max' => trans('validation.max', ['name' => '例文名']),
            // 本文
            'content.required' => trans('validation.required', ['name' => '本文']),
            'content.max' => trans('validation.max', ['name' => '本文']),
        ];

        // ログ出力
        Log::infoOut();

        return $message;
    }
}