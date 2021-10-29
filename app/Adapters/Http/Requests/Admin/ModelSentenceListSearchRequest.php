<?php


namespace App\Adapters\Http\Requests\Admin;


use App\Adapters\Http\Requests\Request;
use App\Utilities\Log;

/**
 * Class ModelSentenceListSearchRequest
 * @package App\Adapters\Http\Requests\Admin
 */
class ModelSentenceListSearchRequest extends Request
{

    public function rules()
    {
        // ログ出力
        Log::infoIn();

        $validators = [
            "modelSentenceType" => ['nullable','array'],
            "modelSentenceName" => ['max:32'],
        ];

        // ログ出力
        Log::infoOut();

        return $validators;
    }

    public function messages()
    {
        // ログ出力
        Log::infoIn();

        $message = [
            // 例文種別
            'modelSentenceType.array' => trans('validation.array', ['name' => '例文種別']),
            // 例文名
            'modelSentenceName.max' => trans('validation.max', ['name' => '例文名']),
        ];

        // ログ出力
        Log::infoOut();

        return $message;
    }
}