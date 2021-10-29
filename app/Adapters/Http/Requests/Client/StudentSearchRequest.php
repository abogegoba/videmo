<?php

namespace App\Adapters\Http\Requests\Client;

use App\Domain\Entities\School;
use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class StudentSearchRequest
 *
 * @package App\Adapters\Http\Requests\Client
 */
class StudentSearchRequest extends Request
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
            // キーワード
            'keywordCondition' => ['nullable', 'max:255', 'halfwidth_kana_control'],
            // 学部系統
            'undergraduateCourseCondition' => ['nullable', 'integer', 'between:1,24'],
            // 希望職種
            'industryCondition' => ['nullable', 'integer', 'between:1,19'],
            // 希望勤務地
            'areaCondition' => ['nullable', 'integer', 'between:1,47'],
            // 卒業年
            'graduationPeriodYear' => ['nullable', 'min:4','date_format:"Y"'],
            // 卒業月
            'graduationPeriodMonth' => ['nullable', 'date_format:"m"'],
            // インターン希望者
            'isInternApplicantCondition' => ['nullable', 'in:1'],
            // 体育会系所属
            'isBelongsAthleticClubCondition' => ['nullable', 'in:1'],
            // 国籍
            'countryCondition' => ['nullable', 'integer', 'between:1,2'],
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
            // キーワード
            'keywordCondition.max' => trans('validation.max', ['name' => 'キーワード']),
            'keywordCondition.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'キーワード']),
            // 学部系統
            'undergraduateCourseCondition.integer' => trans('validation.choice.integer', ['name' => '学部系統']),
            'undergraduateCourseCondition.between' => trans('validation.choice.between', ['name' => '学部系統']),
            // 希望職種
            'industryCondition.integer' => trans('validation.choice.integer', ['name' => '希望職種']),
            'industryCondition.between' => trans('validation.choice.between', ['name' => '希望職種']),
            // 卒業年
            'graduationPeriodYear.date_format' => trans('validation.date_format', ['name' => '卒業年']),
            'graduationPeriodYear.min' => trans('validation.min', ['name' => '卒業年']),
            // 卒業月
            'graduationPeriodMonth.date_format' => trans('validation.date_format', ['name' => '卒業月']),
            // インターン希望
            'isInternApplicantCondition.in' => trans('validation.choice.in', ['name' => 'インターン希望']),
            // 体育会系所属
            'isBelongsAthleticClubCondition.in' => trans('validation.choice.in', ['name' => '体育会系所属']),
            // 国籍
            //'countryCondition.required' => trans('validation.choice.required', ['name' => '国籍']),
            'countryCondition.integer' => trans('validation.choice.integer', ['name' => '国籍']),
            'countryCondition.between' => trans('validation.choice.between', ['name' => '国籍']),
        ];

        Log::infoOut();

        return $messages;
    }
}
