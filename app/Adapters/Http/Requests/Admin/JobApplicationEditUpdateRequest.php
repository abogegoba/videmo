<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Adapters\Http\Requests\Request;
use App\Utilities\Log;

/**
 * Class JobApplicationEditUpdateRequest
 *
 * @package App\Adapters\Http\Requests\Admin
 */
class JobApplicationEditUpdateRequest extends JobApplicationCreateStoreRequest
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

        $createValidators = parent::rules();

        $validators = array_merge($createValidators, $editValidators = [
            // 対象企業
            'companyName' => ['nullable', 'max:255', 'halfwidth_kana_control'],
        ]);

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

        $createMessages = parent::messages();

        $messages = array_merge($createMessages, $editMessages = [
            // 対象企業
            'companyName.max' => trans('validation.max', ['name' => '対象企業']),
            'companyName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '対象企業']),
        ]);

        //ログ出力
        Log::infoOut();

        return $messages;
    }
}