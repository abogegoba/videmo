<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class ClientEntryOneCreateStoreRequest
 *
 * @package App\Adapters\Http\Requests\Client
 */
class ClientContactSendRequest extends Request
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
            // お問合せ項目
            'kind' => ['required'],
            // 氏名(姓)
            'lastName' => ['required', 'max:16', 'halfwidth_kana_control'],
            // 氏名(名)
            'firstName' => ['required', 'max:16', 'halfwidth_kana_control'],
            // 氏名かな(せい)
            'lastNameKana' => ['required', 'max:16', 'regex:/^[ぁ-ゞ]+$/u'],
            // 氏名かな(めい)
            'firstNameKana' => ['required', 'max:16', 'regex:/^[ぁ-ゞ]+$/u'],
            // 会社名
            'companyName' => ['required', 'max:56', 'halfwidth_kana_control'],
            // 部署名
            'departmentName' => ['required', 'max:56', 'halfwidth_kana_control'],
            // 電話番号
            'tel' => ['required', 'min:8', 'max:15', 'regex:/^[0-9]+$/'],
            // メールアドレス
            'mail' => ['required', 'email', 'max:255'],
            // メールアドレス(確認用)
            'confirmMail' => ['required', 'email', 'same:mail', 'max:256'],
            // お問合せ内容
            'contact' => ['required', "max:400", 'halfwidth_kana_control'],
            // ご利用規約に同意する
            'consent' => ['required', 'in:1'],
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
            // お問合せ項目
            'kind.required' => trans('validation.choice.required', ['name' => 'お問合せ項目']),
            // 氏名(姓)
            'lastName.required' => trans('validation.required', ['name' => '氏名(姓)']),
            'lastName.max' => trans('validation.max', ['name' => '氏名(姓)']),
            'lastName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '氏名(姓)']),
            // 氏名(名)
            'firstName.required' => trans('validation.required', ['name' => '氏名(名)']),
            'firstName.max' => trans('validation.max', ['name' => '氏名(名)']),
            'firstName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '氏名(名)']),
            // 氏名かな(せい)
            'lastNameKana.required' => trans('validation.required', ['name' => '氏名かな(せい)']),
            'lastNameKana.max' => trans('validation.max', ['name' => '氏名かな(せい)']),
            'lastNameKana.regex' => trans('validation.other.kana', ['name' => '氏名かな(せい)']),
            // 氏名かな(めい)
            'firstNameKana.required' => trans('validation.required', ['name' => '氏名かな(めい)']),
            'firstNameKana.max' => trans('validation.max', ['name' => '氏名かな(めい)']),
            'firstNameKana.regex' => trans('validation.other.kana', ['name' => '氏名かな(めい)']),
            // 会社名
            'companyName.required' => trans('validation.required', ['name' => '会社名']),
            'companyName.max' => trans('validation.max', ['name' => '会社名']),
            'companyName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '会社名']),
            // 部署名
            'departmentName.required' => trans('validation.required', ['name' => '部署名']),
            'departmentName.max' => trans('validation.max', ['name' => '部署名']),
            'departmentName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '部署名']),
            // 電話番号
            'tel.required' => trans('validation.required', ['name' => '電話番号']),
            'tel.min' => trans('validation.min', ['name' => '電話番号']),
            'tel.max' => trans('validation.max', ['name' => '電話番号']),
            'tel.regex' => trans('validation.regex', ['name' => '電話番号']),
            // メールアドレス
            'mail.required' => trans('validation.required', ['name' => 'メールアドレス']),
            'mail.email' => trans('validation.email', ['name' => 'メールアドレス']),
            'mail.max' => trans('validation.max', ['name' => 'メールアドレス']),
            // メールアドレス(確認用)
            'confirmMail.required' => trans('validation.required', ['name' => 'メールアドレス(確認用)']),
            'confirmMail.email' => trans('validation.email', ['name' => 'メールアドレス(確認用)']),
            'confirmMail.same' => trans('validation.required', ['name' => 'メールアドレス(確認用)']),
            'confirmMail.max' => trans('validation.max', ['name' => 'メールアドレス(確認用)']),
            // お問合せ内容
            'contact.required' => trans('validation.required', ['name' => 'お問合せ内容']),
            'contact.max' => trans('validation.max', ['name' => 'お問合せ内容']),
            'contact.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'お問合せ内容']),
            // ご利用規約に同意する
            'consent.required' => trans('validation.other.agreement_required'),
            'consent.in' => trans('validation.choice.in', ['name' => 'チェック']),
        ];

        //ログ出力
        Log::infoOut();

        return $messages;
    }
}