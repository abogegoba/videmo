<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Domain\Entities\Company;
use App\Adapters\Http\Requests\Request;
use App\Utilities\Log;

/**
 * Class CompanyListSearchRequest
 *
 * @package App\Adapters\Http\Requests\Admin
 */
class CompanyListSearchRequest extends Request
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

        $companyStatusListKeys = array_keys(Company::COMPANY_STATUS_LIST);
        $stringCompanyStatusListKeys = implode(',',$companyStatusListKeys);
        $companyJobApplicationAvailableNumberListKeys = array_keys(Company::JOB_APPLICATION_AVAILABLE_NUMBERS);
        $stringCompanyJobApplicationAvailableNumberListKeys = implode(',',$companyJobApplicationAvailableNumberListKeys);

        $validators = [
            // 会社名
            'companyName' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // 会社名かな
            'companyNameKana' => ['nullable', 'max:255', 'regex:/^[ぁ-ゞ1234567890\-\^\\¥\[\]_\@:\/;\.,!"#\$%&\'\(\)0=~\|\{\}_`\*\?\+><１２３４５６７８９０−＾￥「」＿＠：・；。、！”＃＄％＆’（）０＝〜｜｛｝＿｀＊？＋＞＜ー\-―‐ 　]+$/u'],
            // ステータス
            'companyStatus' => ['nullable', 'in:'. $stringCompanyStatusListKeys],
            // 求人枠（最小値）
            'minJobApplicationAvailableNumber' => ['nullable', 'in:'. $stringCompanyJobApplicationAvailableNumberListKeys],
            // 求人枠（最大値）
            'maxJobApplicationAvailableNumber' => ['nullable', 'in:'. $stringCompanyJobApplicationAvailableNumberListKeys],

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
            'companyName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '会社名']),
            // 会社名かな
            'companyNameKana.max' => trans('validation.max', ['name' => '会社名かな']),
            'companyNameKana.regex' => trans('validation.other.kana', ['name' => '会社名かな']),
            // ステータス
            'companyStatus.in' => trans('validation.choice.in', ['name' => 'ステータス']),
            // 求人枠（最小値）
            'minJobApplicationAvailableNumber.in' => trans('validation.choice.in', ['name' => '求人枠（最小値）']),
            // 求人枠（最大値）
            'maxJobApplicationAvailableNumber.in' => trans('validation.choice.in', ['name' => '求人枠（最大値）']),
        ];

        //ログ出力
        Log::infoOut();
        return $messages;
    }
}