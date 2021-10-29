<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class CompanyRecruitingCreateStoreRequest
 *
 * @package App\Adapters\Http\Requests\Client
 */
class CompanyRecruitingCreateStoreRequest extends Request
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
            'title' => ['required', "max:400", 'halfwidth_kana_control'],
            // 募集職種
            'jobType' => ['required', 'integer', 'between:1,20'],
            // 募集職種説明
            'recruitmentJobTypeDescription' => ['required', "max:400", 'halfwidth_kana_control'],
            // 仕事内容
            'jobDescription' => ['required', "max:400", 'halfwidth_kana_control'],
            // 雇用形態
            'employmentType' => ['required', "in:10,20"],
            // 求める人物像
            'statue' => ['required', "max:400", 'halfwidth_kana_control'],
            // 選考方法
            'screeningMethod' => ['required', "max:400", 'halfwidth_kana_control'],
            // 給与
            'compensation' => ['required', "max:400", 'halfwidth_kana_control'],
            // 賞与/昇給/手当
            'bonus' => ['required', "max:400", 'halfwidth_kana_control'],
            // 勤務地（1）
            'area1' => ['required', 'integer', 'between:1,47'],
            // 勤務地（2）
            'area2' => ['nullable', 'integer', 'between:1,47','different:area1'],
            // 勤務地（3）
            'area3' => ['nullable', 'integer', 'between:1,47','different:area1,area2'],
            // 勤務地（4）
            'area4' => ['nullable', 'integer', 'between:1,47','different:area1,area2,area3'],
            // 勤務地（5）
            'area5' => ['nullable', 'integer', 'between:1,47','different:area1,area2,area3,area4'],
            // 勤務地（6）
            'area6' => ['nullable', 'integer', 'between:1,47','different:area1,area2,area3,area4,area5'],
            // 勤務地（7）
            'area7' => ['nullable', 'integer', 'between:1,47','different:area1,area2,area3,area4,area5,area6'],
            // 勤務地（8）
            'area8' => ['nullable', 'integer', 'between:1,47','different:area1,area2,area3,area4,area5,area6,area7'],
            // 勤務地（9）
            'area9' => ['nullable', 'integer', 'between:1,47','different:area1,area2,area3,area4,area5,area6,area7,area8'],
            // 勤務地（10）
            'area10' => ['nullable', 'integer', 'between:1,47','different:area1,area2,area3,area4,area5,area6,area7,area8,area9'],
            // 勤務時間
            'dutyHours' => ['required', "max:400", 'halfwidth_kana_control'],
            // 待遇／福利厚生
            'compensationPackage' => ['required', "max:400", 'halfwidth_kana_control'],
            // 休日・休暇
            'nonWorkDay' => ['required', "max:400", 'halfwidth_kana_control'],
            // 採用予定人数
            'recruitmentNumber' => ['required', "max:9999", 'halfwidth_kana_control', 'digits_between:1,4'],
            // ステータス
            'status' => ['required', "in:10,20"],
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
            // 求人タイトル
            'title.required' => trans('validation.required', ['name' => '求人タイトル']),
            'title.max' => trans('validation.max', ['name' => '求人タイトル']),
            'title.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '求人タイトル']),
            // 募集職種
            'jobType.required' => trans('validation.choice.required', ['name' => '志望業種']),
            'jobType.integer' => trans('validation.choice.integer', ['name' => '志望業種']),
            'jobType.between' => trans('validation.choice.between', ['name' => '志望業種']),
            // 募集職種説明
            'recruitmentJobTypeDescription.required' => trans('validation.required', ['name' => '募集職種説明']),
            'recruitmentJobTypeDescription.max' => trans('validation.max', ['name' => '募集職種説明']),
            'recruitmentJobTypeDescription.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '募集職種説明']),
            // 仕事内容
            'jobDescription.required' => trans('validation.required', ['name' => '仕事内容']),
            'jobDescription.max' => trans('validation.max', ['name' => '仕事内容']),
            'jobDescription.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '仕事内容']),
            // 雇用形態
            'employmentType.required' => trans('validation.choice.required', ['name' => '雇用形態']),
            'employmentType.in' => trans('validation.choice.in', ['name' => '雇用形態']),
            // 求める人物像
            'statue.required' => trans('validation.required', ['name' => '求める人物像']),
            'statue.max' => trans('validation.max', ['name' => '求める人物像']),
            'statue.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '求める人物像']),
            // 選考方法
            'screeningMethod.required' => trans('validation.required', ['name' => '選考方法']),
            'screeningMethod.max' => trans('validation.max', ['name' => '選考方法']),
            'screeningMethod.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '選考方法']),
            // 給与
            'compensation.required' => trans('validation.required', ['name' => '給与']),
            'compensation.max' => trans('validation.max', ['name' => '給与']),
            'compensation.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '給与']),
            // 賞与/昇給/手当
            'bonus.required' => trans('validation.required', ['name' => '賞与/昇給/手当']),
            'bonus.max' => trans('validation.max', ['name' => '賞与/昇給/手当']),
            'bonus.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '賞与/昇給/手当']),
            // 勤務地（1）
            'area1.required' => trans('validation.choice.required', ['name' => '勤務地（1）']),
            'area1.integer' => trans('validation.choice.integer', ['name' => '勤務地（1）']),
            'area1.between' => trans('validation.choice.between', ['name' => '勤務地（1）']),
            // 勤務地（2）
            'area2.integer' => trans('validation.choice.integer', ['name' => '勤務地（2）']),
            'area2.between' => trans('validation.choice.between', ['name' => '勤務地（2）']),
            'area2.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務地（3）
            'area3.integer' => trans('validation.choice.integer', ['name' => '勤務地（3）']),
            'area3.between' => trans('validation.choice.between', ['name' => '勤務地（3）']),
            'area3.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務地（4）
            'area4.integer' => trans('validation.choice.integer', ['name' => '勤務地（4）']),
            'area4.between' => trans('validation.choice.between', ['name' => '勤務地（4）']),
            'area4.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務地（5）
            'area5.integer' => trans('validation.choice.integer', ['name' => '勤務地（5）']),
            'area5.between' => trans('validation.choice.between', ['name' => '勤務地（5）']),
            'area5.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務地（6）
            'area6.integer' => trans('validation.choice.integer', ['name' => '勤務地（6）']),
            'area6.between' => trans('validation.choice.between', ['name' => '勤務地（6）']),
            'area6.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務地（7）
            'area7.integer' => trans('validation.choice.integer', ['name' => '勤務地（7）']),
            'area7.between' => trans('validation.choice.between', ['name' => '勤務地（7）']),
            'area7.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務地（8）
            'area8.integer' => trans('validation.choice.integer', ['name' => '勤務地（8）']),
            'area8.between' => trans('validation.choice.between', ['name' => '勤務地（8）']),
            'area8.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務地（9）
            'area9.integer' => trans('validation.choice.integer', ['name' => '勤務地（9）']),
            'area9.between' => trans('validation.choice.between', ['name' => '勤務地（9）']),
            'area9.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務地（10）
            'area10.integer' => trans('validation.choice.integer', ['name' => '勤務地（10）']),
            'area10.between' => trans('validation.choice.between', ['name' => '勤務地（10）']),
            'area10.different' => trans('validation.choice.different', ['name' => '勤務地']),
            // 勤務時間
            'dutyHours.required' => trans('validation.required', ['name' => '勤務時間']),
            'dutyHours.max' => trans('validation.max', ['name' => '勤務時間']),
            'dutyHours.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '勤務時間']),
            // 待遇／福利厚生
            'compensationPackage.required' => trans('validation.required', ['name' => '待遇／福利厚生']),
            'compensationPackage.max' => trans('validation.max', ['name' => '待遇／福利厚生']),
            'compensationPackage.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '待遇／福利厚生']),
            // 休日・休暇
            'nonWorkDay.required' => trans('validation.required', ['name' => '休日・休暇']),
            'nonWorkDay.max' => trans('validation.max', ['name' => '休日・休暇']),
            'nonWorkDay.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '休日・休暇']),
            // 採用予定人数
            'recruitmentNumber.required' => trans('validation.required', ['name' => '採用予定人数']),
            'recruitmentNumber.max' => trans('validation.max', ['name' => '採用予定人数']),
            'recruitmentNumber.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '採用予定人数']),
            'recruitmentNumber.digits_between' => trans('validation.digits_between', ['name' => '採用予定人数']),
            // ステータス
            'status.required' => trans('validation.choice.required', ['name' => 'ステータス']),
            'status.in' => trans('validation.choice.in', ['name' => 'ステータス']),
        ];

        Log::infoOut();

        return $messages;
    }
}