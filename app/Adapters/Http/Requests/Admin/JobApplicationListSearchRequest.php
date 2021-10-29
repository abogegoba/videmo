<?php


namespace App\Adapters\Http\Requests\Admin;


use App\Adapters\Http\Requests\Request;
use App\Utilities\Log;

class JobApplicationListSearchRequest extends Request
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
            // 会社名
            'companyName' => ['nullable', 'max:255'],
            // 会社名かな
            'companyNameKana' => ['nullable', 'max:255'],
            // ステータス
            'status' => ['nullable','array'],
            // 勤務地
            'area' => ['nullable', 'integer', 'between:1,47']
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
            // 会社名
            'companyName.max' => trans('validation.max', ['name' => '会社名']),
            // 会社名かな
            'companyNameKana.max' => trans('validation.max', ['name' => '会社名かな']),
            // ステータス
            'status.array' => trans('validation.choice.array',['name'=>'ステータス']),
            // 勤務地
            'area.integer' => trans('validation.choice.integer',['name'=>'勤務地']),
            'area.between' => trans('validation.choice.between' ,['name'=>'勤務地']),
        ];

        //ログ出力
        Log::infoOut();
        return $messages;
    }

}