<?php

namespace App\Adapters\Http\Requests\Admin;

use App\Domain\Entities\Company;
use App\Domain\Entities\Member;
use App\Domain\Entities\School;
use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class MemberOverseasCreateStoreRequest
 *
 * @package App\Adapters\Http\Requests\Admin
 */
class MemberOverseasCreateStoreRequest extends Request
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

        $schoolTypeListKeys = array_keys(School::SCHOOL_TYPE_LIST);
        $stringSchoolTypeListKeys = implode(',', $schoolTypeListKeys);
        $facultyTypeListKeys = array_keys(School::OVERSEAS_FACULTY_TYPE_LIST);
        $stringFacultyTypeListKeys = implode(',', $facultyTypeListKeys);

        $validators = [
            // 氏名(姓)
            'lastName' => ['required', 'max:16', 'halfwidth_kana_control'],
            // 氏名(名)
            'firstName' => ['required', 'max:16', 'halfwidth_kana_control'],
            // 氏名かな(せい)
            'lastNameKana' => ['required', 'max:16', 'katakana'],
            // 氏名かな(めい)
            'firstNameKana' => ['required', 'max:16', 'katakana'],
            // english name
            'englishName' => ['max:32', 'english_alphabet'],
            // 生年月日
            'birthday' => ['required', 'date_format:"Y/m/d"'],
            // 国籍
            'country' => ['required', 'integer','between:2,10'],
            // 郵便番号
            'zipCode' => ['required', 'digits_between:3,7', 'regex:/^[0-9]+$/'],
            // 都道府県
            //'prefecture' => ['required', 'integer', 'between:1,47'],
            // 市区町村
            'city' => ['required', 'max:56', 'halfwidth_kana_control'],
            // 番地・建物名・部屋番号など
            //'blockNumber' => ['nullable', 'max:56', 'halfwidth_kana_control'],
            // 電話番号
            'phoneNumber' => ['required', 'min:8', 'max:15', 'regex:/^[0-9]+$/'],
            // 学校種別
            'schoolType' => ['required', 'integer', 'in:' . $stringSchoolTypeListKeys],
            // 学校名
            'schoolName' => ['required', 'max:72'],
            // 学部・学科名
            'departmentName' => ['required', 'max:72'],
            // 学部系統
            'facultyType' => ['required', 'integer', 'in:' . $stringFacultyTypeListKeys],
            // 卒業年
            'graduationPeriodYear' => ['required', 'min:4', 'date_format:"Y"'],
            // 卒業月
            'graduationPeriodMonth' => ['required', 'date_format:"m"'],
            // 証明写真名
            'idPhotoName' => ['nullable', 'max:250'],
            // 証明写真パス
            'idPhotoPath' => ['nullable', 'max:250'],
            // プライベート写真名
            'privatePhotoName' => ['nullable', 'max:250'],
            // プライベート写真パス
            'privatePhotoPath' => ['nullable', 'max:250'],
            // ハッシュタグ
            //'hashTag' => ['required', 'max:16', 'halfwidth_kana_control', 'input_hash'],
            'hashTag' => ['nullable', 'max:16', 'halfwidth_kana_control', 'input_hash'],
            // ハッシュタグカラー
            //'hashTagColor' => ['required', 'integer', 'between:1,5'],
            'hashTagColor' => ['nullable', 'integer', 'between:1,5'],
            // メールアドレス
            'mailAddress' => ['required', 'email', 'max:255'],
            // パスワード
            'password' => ['required', 'min:6', 'max:14', 'regex:/^[!-~]+$/'],
            // PR動画名
            'prVideoNames' => ['nullable', 'array'],
            'prVideoNames.*' => ['nullable', 'max:250'],
            // PR動画パス
            'prVideoPaths' => ['nullable', 'array'],
            'prVideoPaths.*' => ['nullable', 'max:500'],
            // PR動画タイトル
            'prVideoTitles' => ['nullable', 'array'],
            'prVideoTitles.*' => ['nullable', 'max:24', 'halfwidth_kana_control'],
            // PR動画説明
            'prVideoDescriptions' => ['nullable', 'array'],
            'prVideoDescriptions.*' => ['nullable', 'max:400', 'halfwidth_kana_control'],
            // 自己PR文
            'introduction' => ['nullable', 'max:400', 'halfwidth_kana_control'],
            // 体育会系所属経験
            //'affiliationExperience' => ['required', 'in:' . implode(',', array_keys(Member::AFFILIATION_EXPERIENCE_LABEL_LIST))],
            // インスタフォロワー数
            //'instagramFollowerNumber' => ['required', 'in:' . implode(',', array_keys(Member::INSTAGRAM_FOLLOWER_NUMBER_LABEL_LIST))],
            'instagramFollowerNumber' => ['nullable', 'in:' . implode(',', array_keys(Member::INSTAGRAM_FOLLOWER_NUMBER_LABEL_LIST))],
            // 自己紹介
            'selfIntroductions' => ['nullable', 'array'],
            'selfIntroductions.*' => ['nullable', 'max:400', 'halfwidth_kana_control'],
            // 自己紹介（自由入力タイトル）
            'selfIntroduction10Title' => ['max:24', 'halfwidth_kana_control'],
            // 志望業種
            'industry1' => ['required', 'integer', 'between:1,99'],
            'industry2' => ['nullable', 'integer', 'between:1,99', 'different:industry1'],
            'industry3' => ['nullable', 'integer', 'between:1,99', 'different:industry1,industry2'],
            // 志望職種
            //'jobType1' => ['required', 'integer', 'between:1,47'],
            'jobType1' => ['nullable', 'integer', 'between:1,47'],
            'jobType2' => ['nullable', 'integer', 'between:1,47', 'different:jobType1'],
            'jobType3' => ['nullable', 'integer', 'between:1,47', 'different:jobType1,jobType2'],
            // 志望勤務地
            'location1' => ['required', 'integer', 'between:1,47'],
            'location2' => ['nullable', 'integer', 'between:1,47', 'different:location1'],
            'location3' => ['nullable', 'integer', 'between:1,47', 'different:location1,location2'],
            // インターン希望
            'internNeeded' => ['required', 'in:0,1'],
            // TOEIC
            'toeicScore' => ['nullable', 'digits_between:1,3'],
            // TOEFL
            'toeflScore' => ['nullable', 'digits_between:1,3'],
            // 保有資格・検定など
            'certificationList' => ['nullable', 'array'],
            'certificationList.*' => ['nullable', 'max:32', 'halfwidth_kana_control'],
            // 経歴年
            'careerPeriodYears' => ['nullable', 'array'],
            'careerPeriodYears.*' => ['nullable', 'min:4', 'date_format:"Y"'],
            // 経歴月
            'careerPeriodMonths' => ['nullable', 'array'],
            'careerPeriodMonths.*' => ['nullable', 'date_format:"m"'],
            // 経歴
            'careerNames' => ['nullable', 'array'],
            'careerNames.*' => ['nullable', 'max:32', 'halfwidth_kana_control'],
            // 管理メモ
            'managementMemo' => ['nullable', 'max:4000'],
            // ステータス
            'status' => ['required', 'integer', 'in:' . Member::STATUS_TEMPORARY_MEMBER . "," . Member::STATUS_REAL_MEMBER . "," . Member::STATUS_WITHDRAWN_MEMBER],
            // メール送信
            'sendMail' => ['required', 'integer', 'in:0,1'],
        ];

        //ログ出力
        Log::infoOut();

        return $validators;
    }

    /**
     * 関連バリデーション
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        //ログ出力
        Log::infoIn();

        // 年が存在しているとき
        $periodYears = $this->get("careerPeriodYears");
        if (!empty($periodYears)) {
            foreach ($periodYears as $key => $periodYear) {
                $validator->sometimes('careerPeriodMonths.' . $key, ['required'], function ($input) use ($periodYear) {
                    return !empty($periodYear);
                });
                $validator->sometimes('careerNames.' . $key, ['required'], function ($input) use ($periodYear) {
                    return !empty($periodYear);
                });
            }
        }

        // 月が存在しているとき
        $periodMonths = $this->get("careerPeriodMonths");
        if (!empty($periodMonths)) {
            foreach ($periodMonths as $key => $periodMonth) {
                $validator->sometimes('careerPeriodYears.' . $key, ['required'], function ($input) use ($periodMonth) {
                    return !empty($periodMonth);
                });
                $validator->sometimes('careerNames.' . $key, ['required'], function ($input) use ($periodMonth) {
                    return !empty($periodMonth);
                });
            }
        }

        // 名前が存在しているとき
        $names = $this->get("careerNames");
        if (!empty($names)) {
            foreach ($names as $key => $name) {
                $validator->sometimes('careerPeriodMonths.' . $key, ['required'], function ($input) use ($name) {
                    return !empty($name);
                });
                $validator->sometimes('careerPeriodYears.' . $key, ['required'], function ($input) use ($name) {
                    return !empty($name);
                });
            }
        }

        //ログ出力
        Log::infoOut();
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
            // 氏名(姓)
            'lastName.required' => trans('validation.required', ['name' => '氏名(姓)']),
            'lastName.max' => trans('validation.max', ['name' => '氏名(姓)']),
            'lastName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '氏名(姓)']),
            // 氏名(名)
            'firstName.required' => trans('validation.required', ['name' => '氏名(名)']),
            'firstName.max' => trans('validation.max', ['name' => '氏名(名)']),
            'firstName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '氏名(名)']),
            // 氏名(セイ)
            'lastNameKana.required' => trans('validation.required', ['name' => '氏名(セイ)']),
            'lastNameKana.max' => trans('validation.max', ['name' => '氏名(セイ)']),
            'lastNameKana.katakana' => trans('validation.other.katakana', ['name' => '氏名(セイ)']),
            // 氏名(メイ)
            'firstNameKana.required' => trans('validation.required', ['name' => '氏名(メイ)']),
            'firstNameKana.max' => trans('validation.max', ['name' => '氏名(メイ)']),
            'firstNameKana.katakana' => trans('validation.other.katakana', ['name' => '氏名(メイ)']),
            // English name
            'englishName.max' => trans('validation.max', ['name' => 'English name']),
            'englishName.english_alphabet' => trans('validation.other.english_alphabet', ['name' => 'English name']),
            // 誕生日
            'birthday.required' => trans('validation.required', ['name' => '生年月日']),
            'birthday.date_format' => trans('validation.date_format', ['name' => '生年月日']),
            // 国籍
            'country.required' => trans('validation.choice.required', ['name' => '国籍']),
            'country.integer' => trans('validation.choice.integer', ['name' => '国籍']),
            'country.between' => trans('validation.choice.between', ['name' => '国籍']),
            // 郵便番号
            'zipCode.required' => trans('validation.required', ['name' => '郵便番号']),
            'zipCode.digits_between' => trans('validation.digits_between', ['name' => '郵便番号']),
            'zipCode.regex' => trans('validation.regex', ['name' => '郵便番号']),
            // 都道府県
            //'prefecture.required' => trans('validation.choice.required', ['name' => '都道府県']),
            //'prefecture.integer' => trans('validation.choice.integer', ['name' => '都道府県']),
            //'prefecture.between' => trans('validation.choice.between', ['name' => '都道府県']),
            // 市区町村
            'city.required' => trans('validation.required', ['name' => '住所']),
            'city.max' => trans('validation.max', ['name' => '住所']),
            'city.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '住所']),
            // 番地・建物名・部屋番号など
            //'blockNumber.max' => trans('validation.max', ['name' => '番地・建物名・部屋番号など']),
            //'blockNumber.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '番地・建物名・部屋番号など']),
            // 電話番号
            'phoneNumber.required' => trans('validation.required', ['name' => '電話番号']),
            'phoneNumber.min' => trans('validation.min', ['name' => '電話番号']),
            'phoneNumber.max' => trans('validation.max', ['name' => '電話番号']),
            'phoneNumber.regex' => trans('validation.integer', ['name' => '電話番号']),
            // 学校種別
            'schoolType.required' => trans('validation.choice.required', ['name' => '学校種別']),
            'schoolType.integer' => trans('validation.choice.integer', ['name' => '学校種別']),
            'schoolType.in' => trans('validation.choice.in', ['name' => '学校種別']),
            // 学校名
            'schoolName.required' => trans('validation.required', ['name' => '学校名']),
            'schoolName.max' => trans('validation.max', ['name' => '学校名']),
            'schoolName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '学校名']),
            // 学部・学科名
            'departmentName.required' => trans('validation.required', ['name' => '学部・学科名']),
            'departmentName.max' => trans('validation.max', ['name' => '学部・学科名']),
            'departmentName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '学部・学科名']),
            // 学部系統
            'facultyType.required' => trans('validation.choice.required', ['name' => '学部系統']),
            'facultyType.integer' => trans('validation.choice.integer', ['name' => '学部系統']),
            'facultyType.in' => trans('validation.choice.in', ['name' => '学部系統']),
            // 卒業年
            'graduationPeriodYear.required' => trans('validation.required', ['name' => '卒業年']),
            'graduationPeriodYear.date_format' => trans('validation.date_format', ['name' => '卒業年']),
            'graduationPeriodYear.min' => trans('validation.min', ['name' => '卒業年']),
            // 卒業月
            'graduationPeriodMonth.required' => trans('validation.required', ['name' => '卒業月']),
            'graduationPeriodMonth.date_format' => trans('validation.date_format', ['name' => '卒業月']),
            // 証明写真
            'idPhoto.required' => trans('validation.required', ['name' => '証明写真']),
            // 証明写真名
            'idPhotoName.max' => trans('validation.max', ['name' => '証明写真名']),
            // 証明写真パス
            'idPhotoPath.max' => trans('validation.max', ['name' => '証明写真パス']),
            // プライベート写真
            'privatePhoto.required' => trans('validation.required', ['name' => 'プライベート写真']),
            // プライベート写真名
            'privatePhotoName.max' => trans('validation.max', ['name' => 'プライベート写真名']),
            // プライベート写真パス
            'privatePhotoPath.max' => trans('validation.max', ['name' => 'プライベート写真パス']),
            // ハッシュタグ
            //'hashTag.required' => trans('validation.required', ['name' => 'ハッシュタグ']),
            'hashTag.max' => trans('validation.max', ['name' => 'ハッシュタグ']),
            'hashTag.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'ハッシュタグ']),
            'hashTag.input_hash' => trans('validation.other.input_hash', ['name' => '#']),
            // ハッシュタグカラー
            'hashTagColor.required' => trans('validation.choice.required', ['name' => 'ハッシュタグカラー']),
            // メールアドレス
            'mailAddress.required' => trans('validation.required', ['name' => 'メールアドレス']),
            'mailAddress.email' => trans('validation.email', ['name' => 'メールアドレス']),
            'mailAddress.max' => trans('validation.max', ['name' => 'メールアドレス']),
            // パスワード
            'password.required' => trans('validation.required', ['name' => 'パスワード']),
            'password.min' => trans('validation.min', ['name' => 'パスワード']),
            'password.max' => trans('validation.max', ['name' => 'パスワード']),
            'password.regex' => trans('validation.other.alpha_num_symbol', ['name' => 'パスワード']),
            // PR動画・画像名
            'prVideoNames.array' => trans('validation.array', ['name' => 'PR動画・画像名']),
            'prVideoNames.*.max' => trans('validation.max', ['name' => 'PR動画・画像名']),
            // PR動画・画像パス
            'prVideoPaths.array' => trans('validation.array', ['name' => 'PR動画・画像パス']),
            'prVideoPaths.*.max' => trans('validation.max', ['name' => 'PR動画・画像パス']),
            // PR動画・画像タイトル
            'prVideoTitles.array' => trans('validation.array', ['name' => 'PR動画・画像タイトル']),
            'prVideoTitles.*.max' => trans('validation.max', ['name' => 'PR動画・画像タイトル']),
            'prVideoTitles.*.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'PR動画・画像タイトル']),
            // PR動画・画像説明
            'prVideoDescriptions.array' => trans('validation.array', ['name' => 'PR動画・画像説明']),
            'prVideoDescriptions.*.max' => trans('validation.max', ['name' => 'PR動画・画像説明']),
            'prVideoDescriptions.*.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'PR動画・画像説明']),
            // 自己PR文
            'introduction.required' => trans('validation.required', ['name' => '自己PR文']),
            'introduction.max' => trans('validation.max', ['name' => '自己PR文']),
            'introduction.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '自己PR文']),
            // 体育会系所属経験
            'affiliationExperience.required' => trans('validation.choice.required', ['name' => '体育会系所属経験']),
            'affiliationExperience.in' => trans('validation.choice.in', ['name' => '体育会系所属経験']),
            // インスタフォロワー数
            //'instagramFollowerNumber.required' => trans('validation.choice.required', ['name' => 'インスタフォロワー数']),
            'instagramFollowerNumber.in' => trans('validation.choice.in', ['name' => 'インスタフォロワー数']),
            // 自己紹介
            'selfIntroductions.array' => trans('validation.array', ['name' => '入力されたデータ']),
            'selfIntroductions.*.max' => trans('validation.max', ['name' => '本文']),
            'selfIntroductions.*.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '本文']),
            // 自己紹介（自由入力タイトル）
            'selfIntroductions.10.required' => trans('validation.required', ['name' => '本文']),
            'selfIntroduction10Title.required' => trans('validation.required', ['name' => 'タイトル']),
            'selfIntroduction10Title.max' => trans('validation.max', ['name' => 'タイトル']),
            'selfIntroduction10Title.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'タイトル']),
            // 志望業種
            'industry1.required' => trans('validation.choice.required', ['name' => '1つ目の志望業種']),
            'industry1.integer' => trans('validation.choice.integer', ['name' => '1つ目の志望業種']),
            'industry1.between' => trans('validation.choice.between', ['name' => '1つ目の志望業種']),
            'industry2.integer' => trans('validation.choice.integer', ['name' => '2つ目の志望業種']),
            'industry2.between' => trans('validation.choice.between', ['name' => '2つ目の志望業種']),
            'industry2.different' => trans('validation.choice.different', ['name' => '志望業種']),
            'industry3.integer' => trans('validation.choice.integer', ['name' => '3つ目の志望業種']),
            'industry3.between' => trans('validation.choice.between', ['name' => '3つ目の志望業種']),
            'industry3.different' => trans('validation.choice.different', ['name' => '志望業種']),
            // 志望職種
            //'jobType1.required' => trans('validation.choice.required', ['name' => '1つ目の志望職種']),
            'jobType1.integer' => trans('validation.choice.integer', ['name' => '1つ目の志望職種']),
            'jobType1.between' => trans('validation.choice.between', ['name' => '1つ目の志望職種']),
            'jobType2.integer' => trans('validation.choice.integer', ['name' => '2つ目の志望職種']),
            'jobType2.between' => trans('validation.choice.between', ['name' => '2つ目の志望職種']),
            'jobType2.different' => trans('validation.choice.different', ['name' => '志望職種']),
            'jobType3.integer' => trans('validation.choice.integer', ['name' => '3つ目の志望職種']),
            'jobType3.between' => trans('validation.choice.between', ['name' => '3つ目の志望職種']),
            'jobType3.different' => trans('validation.choice.different', ['name' => '志望職種']),
            // 志望勤務地
            'location1.required' => trans('validation.choice.required', ['name' => '1つ目の志望勤務地']),
            'location1.integer' => trans('validation.choice.integer', ['name' => '1つ目の志望勤務地']),
            'location1.between' => trans('validation.choice.between', ['name' => '1つ目の志望勤務地']),
            'location2.integer' => trans('validation.choice.integer', ['name' => '2つ目の志望勤務地']),
            'location2.between' => trans('validation.choice.between', ['name' => '2つ目の志望勤務地']),
            'location2.different' => trans('validation.choice.different', ['name' => '志望勤務地']),
            'location3.integer' => trans('validation.choice.integer', ['name' => '3つ目の志望勤務地']),
            'location3.between' => trans('validation.choice.between', ['name' => '3つ目の志望勤務地']),
            'location3.different' => trans('validation.choice.different', ['name' => '志望勤務地']),
            // インターン希望
            'internNeeded.required' => trans('validation.choice.required', ['name' => 'インターン希望']),
            'internNeeded.in' => trans('validation.choice.in', ['name' => 'インターン希望']),
            // TOEIC
            'toeicScore.digits_between' => trans('validation.digits_between', ['name' => 'TOEIC']),
            // TOEFL
            'toeflScore.digits_between' => trans('validation.digits_between', ['name' => 'TOEFL']),
            // 保有資格・検定など
            'certificationList.array' => trans('validation.array', ['name' => '保有資格・検定']),
            'certificationList.*.max' => trans('validation.max', ['name' => '保有資格・検定']),
            'certificationList.*.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '保有資格・検定']),
            // 経歴年
            'careerPeriodYears.array' => trans('validation.choice.array', ['name' => '経歴年']),
            'careerPeriodYears.*.required' => trans('validation.choice.required', ['name' => '経歴年']),
            'careerPeriodYears.*.min' => trans('validation.choice.min', ['name' => '経歴年']),
            'careerPeriodYears.*.date_format' => trans('validation.choice.date_format', ['name' => '経歴年']),
            // 経歴月
            'careerPeriodMonths.array' => trans('validation.choice.array', ['name' => '経歴月']),
            'careerPeriodMonths.*.required' => trans('validation.choice.required', ['name' => '経歴月']),
            'careerPeriodMonths.*.date_format' => trans('validation.choice.date_format', ['name' => '経歴月']),
            // 経歴
            'careerNames.array' => trans('validation.array', ['name' => '経歴月']),
            'careerNames.*.required' => trans('validation.required', ['name' => '経歴']),
            'careerNames.*.max' => trans('validation.max', ['name' => '経歴']),
            'careerNames.*.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '経歴']),
            // 管理メモ
            'managementMemo.max' => trans('validation.max', ['name' => '管理メモ']),
            // ステータス
            'status.required' => trans('validation.choice.required', ['name' => 'ステータス']),
            'status.integer' => trans('validation.choice.integer', ['name' => 'ステータス']),
            'status.in' => trans('validation.choice.in', ['name' => 'ステータス']),
            // メール送信
            'sendMail.required' => trans('validation.choice.required', ['name' => 'メール送信']),
            'sendMail.integer' => trans('validation.choice.integer', ['name' => 'メール送信']),
            'sendMail.in' => trans('validation.choice.in', ['name' => 'メール送信']),
        ];

        //ログ出力
        Log::infoOut();

        return $messages;
    }
}
