<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class CompanyBasicInformationEditUploadRequest
 *
 * @package App\Adapters\Http\Requests\Client
 */
class CompanyBasicInformationEditUploadRequest extends Request
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
            // アップロード画像
            'uploadImage' => ['nullable', 'max:10000000', 'mimes:png,jpeg,jpg'],
            // アップロード動画
            'uploadVideo' => ['nullable', 'max:1073741824', 'mimes:mp4,qt,x-ms-wmv,mpeg,x-msvideo'],
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
            // アップロード画像
            'uploadImage.max' => trans('validation.file.max', ['name' => '画像']),
            'uploadImage.mimes' => trans('validation.file.mimes', ['name' => '画像']),
            // アップロード動画
            'uploadVideo.max' => trans('validation.file.max', ['name' => '動画']),
            'uploadVideo.mimes' => trans('validation.file.mimes', ['name' => '動画']),
        ];

        //ログ出力
        Log::infoOut();

        return $messages;
    }
}
