<?php

namespace App\Adapters\Http\Requests\Client;

use App\Utilities\Log;
use App\Adapters\Http\Requests\Request;

/**
 * Class CompanyBasicInformationEditRequest
 *
 * @package App\Adapters\Http\Requests\Client
 */
class CompanyBasicInformationEditRequest extends Request
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
            // 会社名
            'name' => ['required', 'max:56', 'halfwidth_kana_control'],
            // 会社名かな
            'nameKana' => ['required', 'max:56', 'regex:/^[ぁ-ゞ1234567890\-\^\\¥\[\]_\@:\/;\.,!"#\$%&\'\(\)0=~\|\{\}_`\*\?\+><１２３４５６７８９０−＾￥「」＿＠：・；。、！”＃＄％＆’（）０＝〜｜｛｝＿｀＊？＋＞＜ー\-―‐ 　]+$/u'],
            // 郵便番号
            'zip' => ['required', 'digits:7', 'regex:/^[0-9]+$/'],
            // 都道府県
            'prefectures' => ['required', 'integer', 'between:1,47'],
            // 市区町村
            'city' => ['required', 'max:56', 'halfwidth_kana_control'],
            // 番地・建物名・部屋番号など
            'room' => ['required', 'max:56', 'halfwidth_kana_control'],
            // 業種
            'industryCondition.0' => ['required'],
            'industryCondition.*' => ['between:1,99'],
            // 事業内容
            'descriptionOfBusiness' => ['required', "max:400", 'halfwidth_kana_control'],
            // 設立
            'establishmentDate' => ['required', 'max:24', 'halfwidth_kana_control'],
            // 資本金
            'capital' => ['required', 'max:24', 'halfwidth_kana_control'],
            // 従業員
            'payrollNumber' => ['required', 'max:24', 'halfwidth_kana_control'],
            // 売上高
            'sales' => ['required', 'max:24', 'halfwidth_kana_control'],
            // 代表者
            'representativePerson' => ['required', 'max:24', 'halfwidth_kana_control'],
            // 役員構成
            'exectiveOfficers' => ['required', "max:400", 'halfwidth_kana_control'],
            // 事業所
            'establishment' => ['required', "max:400", 'halfwidth_kana_control'],
            // 関連会社
            'affiliatedCompany' => ['required', "max:400", 'halfwidth_kana_control'],
            // 登録・資格
            'qualification' => ["max:400", 'halfwidth_kana_control'],
            // ホームページURL
            //'homePageUrl' => ['required', 'max:255',  'regex:/^[!-~]+$/'],
            'homePageUrl' => ['nullable', 'max:255',  'regex:/^[!-~]+$/'],
            // 採用ホームページ
            'recruitmentUrl' => ['nullable', 'max:255', 'regex:/^[!-~]+$/'],
            // 主要取引先
            'mainClient' => ['required', "max:400", 'halfwidth_kana_control'],
            // 企業ロゴ名
            'uploadedLogoName' => ['nullable', 'max:250'],
            // 企業ロゴパス
            'uploadedLogoPath' => ['nullable', 'max:250'],
            // 担当者名
            'picName' => ['required', 'max:24', 'halfwidth_kana_control'],
            // 連絡先電話番号
            'picPhoneNumber' => ['required', 'min:10', 'max:15', 'regex:/^[0-9]+$/'],
            // 緊急連絡先電話番号
            'picEmergencyPhoneNumber' => ['required', 'min:10', 'max:15', 'regex:/^[0-9]+$/'],
            // 連絡先メールアドレス
            'picMailAddress' => ['required', 'email', 'max:255'],
            // 企業画像一覧に表示
            'displayImage' => ['required'],
            // 企業画像名
            'companyImageNames' => ['nullable', 'array'],
            'companyImageNames.*' => ['nullable', 'max:250'],
            // 企業画像パス
            'companyImagePaths' => ['nullable', 'array'],
            'companyImagePaths.*' => ['nullable', 'max:250'],
            // 企業紹介文
            'introductorySentence' => ['required', "max:400", 'halfwidth_kana_control'],
            // PR動画名
            'prVideoName' => ['nullable', 'max:250'],
            // PR動画パス
            'prVideoPath' => ['nullable', 'max:250'],
            // 5秒動画名
            'video5sName' => ['nullable', 'max:250'],
            // 5秒動画パス
            'video5sPath' => ['nullable', 'max:250'],
            // 5秒動画サムネイル画像名
            'video5sThumbName' => ['nullable', 'max:250'],
            // 5秒動画サムネイル画像パス
            'video5sThumbPath' => ['nullable', 'max:250'],
            // 5秒動画タイトル
            'video5sTitle' => ['nullable', 'max:9', 'halfwidth_kana_control'],
            // 10秒動画名
            'video10sName' => ['nullable', 'max:250'],
            // 10秒動画パス
            'video10sPath' => ['nullable', 'max:250'],
            // 10秒動画サムネイル画像名
            'video10sThumbName' => ['nullable', 'max:250'],
            // 10秒動画サムネイル画像パス
            'video10sThumbPath' => ['nullable', 'max:250'],
            // 10秒動画タイトル
            'video10sTitle' => ['nullable', 'max:9', 'halfwidth_kana_control'],
            // 15秒動画名
            'video15sName' => ['nullable', 'max:250'],
            // 15秒動画パス
            'video15sPath' => ['nullable', 'max:250'],
            // 15秒動画サムネイル画像名
            'video15sThumbName' => ['nullable', 'max:250'],
            // 15秒動画サムネイル画像パス
            'video15sThumbPath' => ['nullable', 'max:250'],
            // 15秒動画タイトル
            'video15sTitle' => ['nullable', 'max:9', 'halfwidth_kana_control'],
            // 当社の特徴動画・画像名
            'featureNames' => ['nullable', 'array'],
            'featureNames.*' => ['nullable', 'max:250'],
            // 当社の特徴動画・画像パス
            'featurePaths' => ['nullable', 'array'],
            'featurePaths.*' => ['nullable', 'max:250'],
            // 当社の特徴 タイトル
            'featureTitles' => ['nullable', 'array'],
            'featureTitles.*' => ['nullable', 'max:24', 'halfwidth_kana_control'],
            // 当社の特徴 説明文
            'featureDescriptions' => ['nullable', 'array'],
            'featureDescriptions.*' => ['nullable', 'max:400', 'halfwidth_kana_control'],
            // ハッシュタグ
            'hashtag' => ['required', 'max:16', 'halfwidth_kana_control', 'input_hash'],
            // ハッシュタグカラー
            'hashTagColor' => ['required', 'integer', 'between:1,5'],
            // 募集タグ
            'recruitmentTargetYear' => ['nullable', 'in:1'],
            'recruitmentTargetThisYear' => ['nullable', 'in:1'],
            'recruitmentTargetIntern' => ['nullable', 'in:1'],
        ];

        Log::infoOut();

        return $validators;
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    public function withValidator(\Illuminate\Contracts\Validation\Validator $validator)
    {
        //ログ出力
        Log::infoIn();

        // 企業ロゴ
        $validator->sometimes('logo', 'required', function ($input) {
            return empty($input->uploadedLogoName) || empty($input->uploadedLogoPath);
        });

        // 企業画像
        $validator->sometimes('companyImage', 'required', function ($input) {
            if (empty($input->companyImageNames || empty($input->companyImagePaths))) {
                return true;
            } else {
                return empty($input->companyImageNames[0]) || empty($input->companyImagePaths[0]);
            }
        });

        // 5秒動画
        $validator->sometimes('video5sName', 'required', function ($input) {
            return !empty($input->video5sPath) || (!empty($input->video5sThumbName) && !empty($input->video5sThumbPath));
        });
        $validator->sometimes('video5sPath', 'required', function ($input) {
            return !empty($input->video5sName);
        });
        $validator->sometimes('video5sThumbName', 'required', function ($input) {
            return !empty($input->video5sThumbPath);
        });
        $validator->sometimes('video5sThumbPath', 'required', function ($input) {
            return !empty($input->video5sThumbName);
        });

        // 10秒動画
        $validator->sometimes('video10sName', 'required', function ($input) {
            return !empty($input->video10sPath) || (!empty($input->video10sThumbName) && !empty($input->video10sThumbPath));
        });
        $validator->sometimes('video10sPath', 'required', function ($input) {
            return !empty($input->video10sName);
        });
        $validator->sometimes('video10sThumbName', 'required', function ($input) {
            return !empty($input->video10sThumbPath);
        });
        $validator->sometimes('video10sThumbPath', 'required', function ($input) {
            return !empty($input->video10sThumbName);
        });

        // 15秒動画
        $validator->sometimes('video15sName', 'required', function ($input) {
            return !empty($input->video15sPath) || (!empty($input->video15sThumbName) && !empty($input->video15sThumbPath));
        });
        $validator->sometimes('video15sPath', 'required', function ($input) {
            return !empty($input->video15sName);
        });
        $validator->sometimes('video15sThumbName', 'required', function ($input) {
            return !empty($input->video15sThumbPath);
        });
        $validator->sometimes('video15sThumbPath', 'required', function ($input) {
            return !empty($input->video15sThumbName);
        });

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
            // 会社名
            'name.required' => trans('validation.required', ['name' => '会社名']),
            'name.max' => trans('validation.max', ['name' => '会社名']),
            'name.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '会社名']),
            // 会社名かな
            'nameKana.required' => trans('validation.required', ['name' => '会社名']),
            'nameKana.max' => trans('validation.max', ['name' => '会社名']),
            'nameKana.regex' => trans('validation.other.kana', ['name' => '会社名']),
            // 郵便番号
            'zip.required' => trans('validation.required', ['name' => '郵便番号']),
            'zip.digits' => trans('validation.digits', ['name' => '郵便番号']),
            'zip.regex' => trans('validation.regex', ['name' => '郵便番号']),
            // 都道府県
            'prefectures.required' => trans('validation.choice.required', ['name' => '都道府県']),
            'prefectures.integer' => trans('validation.choice.integer', ['name' => '都道府県']),
            'prefectures.between' => trans('validation.choice.between', ['name' => '都道府県']),
            // 市区町村
            'city.required' => trans('validation.required', ['name' => '市区町村']),
            'city.max' => trans('validation.max', ['name' => '市区町村']),
            'city.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '市区町村']),
            // 番地・建物名・部屋番号など
            'room.required' => trans('validation.required', ['name' => '番地・建物名・部屋番号など']),
            'room.max' => trans('validation.max', ['name' => '番地・建物名・部屋番号など']),
            'room.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '番地・建物名・部屋番号など']),
            // 業種
            'industryCondition.*.required' => trans('validation.choice.required', ['name' => '業種']),
            'industryCondition.*.integer' => trans('validation.choice.integer', ['name' => '業種']),
            'industryCondition.*.between' => trans('validation.choice.between', ['name' => '業種']),
            // 事業内容
            'descriptionOfBusiness.required' => trans('validation.required', ['name' => '事業内容']),
            'descriptionOfBusiness.max' => trans('validation.max', ['name' => '事業内容']),
            'descriptionOfBusiness.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '事業内容']),
            // 設立
            'establishmentDate.required' => trans('validation.required', ['name' => '設立']),
            'establishmentDate.max' => trans('validation.max', ['name' => '設立']),
            'establishmentDate.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '設立']),
            // 資本金
            'capital.required' => trans('validation.required', ['name' => '資本金']),
            'capital.max' => trans('validation.max', ['name' => '資本金']),
            'capital.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '資本金']),
            // 従業員
            'payrollNumber.required' => trans('validation.required', ['name' => '従業員']),
            'payrollNumber.max' => trans('validation.max', ['name' => '従業員']),
            'payrollNumber.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '従業員']),
            // 売上高
            'sales.required' => trans('validation.required', ['name' => '売上高']),
            'sales.max' => trans('validation.max', ['name' => '売上高']),
            'sales.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '売上高']),
            // 代表者
            'representativePerson.required' => trans('validation.required', ['name' => '代表者']),
            'representativePerson.max' => trans('validation.max', ['name' => '代表者']),
            'representativePerson.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '代表者']),
            // 役員構成
            'exectiveOfficers.required' => trans('validation.required', ['name' => '役員構成']),
            'exectiveOfficers.max' => trans('validation.max', ['name' => '役員構成']),
            'exectiveOfficers.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '役員構成']),
            // 事業所
            'establishment.required' => trans('validation.required', ['name' => '事業所']),
            'establishment.max' => trans('validation.max', ['name' => '事業所']),
            'establishment.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '事業所']),
            // 関連会社
            'affiliatedCompany.required' => trans('validation.required', ['name' => '関連会社']),
            'affiliatedCompany.max' => trans('validation.max', ['name' => '関連会社']),
            'affiliatedCompany.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '関連会社']),
            // 登録・資格
            'qualification.max' => trans('validation.max', ['name' => '登録・資格']),
            'qualification.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '登録・資格']),
            // ホームページURL
            //'homePageUrl.required' => trans('validation.required', ['name' => 'ホームページURL']),
            'homePageUrl.max' => trans('validation.max', ['name' => 'ホームページURL']),
            'homePageUrl.regex' => trans('validation.other.alpha_num_symbol', ['name' => 'ホームページURL']),
            // 採用ホームページ
            'recruitmentUrl.regex' => trans('validation.other.alpha_num_symbol', ['name' => '採用ホームページ']),
            'recruitmentUrl.max' => trans('validation.max', ['name' => '採用ホームページ']),
            // 主要取引先
            'mainClient.required' => trans('validation.required', ['name' => '主要取引先']),
            'mainClient.max' => trans('validation.max', ['name' => '主要取引先']),
            'mainClient.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '主要取引先']),
            // 企業ロゴ
            'logo.required' => trans('validation.choice.required', ['name' => '企業ロゴ']),
            // 企業ロゴ名
            'uploadedLogoName.max' => trans('validation.max', ['name' => '企業ロゴ名']),
            // 企業ロゴパス
            'uploadedLogoPath.max' => trans('validation.max', ['name' => '企業ロゴパス']),
            // 担当者名
            'picName.required' => trans('validation.required', ['name' => '担当者名']),
            'picName.max' => trans('validation.max', ['name' => '担当者名']),
            'picName.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '担当者名']),
            // 連絡先電話番号
            'picPhoneNumber.required' => trans('validation.required', ['name' => '連絡先電話番号']),
            'picPhoneNumber.min' => trans('validation.min', ['name' => '連絡先電話番号']),
            'picPhoneNumber.max' => trans('validation.max', ['name' => '連絡先電話番号']),
            'picPhoneNumber.regex' => trans('validation.integer', ['name' => '連絡先電話番号']),
            // 緊急連絡先電話番号
            'picEmergencyPhoneNumber.required' => trans('validation.required', ['name' => '緊急連絡先電話番号']),
            'picEmergencyPhoneNumber.min' => trans('validation.min', ['name' => '緊急連絡先電話番号']),
            'picEmergencyPhoneNumber.max' => trans('validation.max', ['name' => '緊急連絡先電話番号']),
            'picEmergencyPhoneNumber.regex' => trans('validation.integer', ['name' => '緊急連絡先電話番号']),
            // 連絡先メールアドレス
            'picMailAddress.required' => trans('validation.required', ['name' => '連絡先メールアドレス']),
            'picMailAddress.email' => trans('validation.email', ['name' => '連絡先メールアドレス']),
            'picMailAddress.max' => trans('validation.max', ['name' => '連絡先メールアドレス']),
            // 一覧に表示
            'displayImage.required' => trans('validation.choice.required', ['name' => '一覧に表示']),
            // 企業画像
            'companyImage.required' => trans('validation.choice.required', ['name' => '企業画像']),
            // 企業画像名
            'companyImageNames.array' => trans('validation.array', ['name' => '企業画像名']),
            'companyImageNames.*.max' => trans('validation.max', ['name' => '企業画像名']),
            // 企業画像パス
            'companyImagePaths.array' => trans('validation.array', ['name' => '企業画像パス']),
            'companyImagePaths.*.max' => trans('validation.max', ['name' => '企業画像パス']),
            // 企業紹介文
            'introductorySentence.required' => trans('validation.required', ['name' => '企業紹介文']),
            'introductorySentence.max' => trans('validation.max', ['name' => '企業紹介文']),
            'introductorySentence.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '企業紹介文']),
            // PR動画名
            'prVideoName.max' => trans('validation.max', ['name' => 'PR動画名']),
            // PR動画パス
            'prVideoPath.max' => trans('validation.max', ['name' => 'PR動画パス']),
            // 5秒動画名
            'video5sName.required' => trans('validation.choice.required', ['name' => '5秒動画']),
            'video5sName.max' => trans('validation.max', ['name' => '5秒動画名']),
            // 5秒動画パス
            'video5sPath.required' => trans('validation.choice.required', ['name' => '5秒動画']),
            'video5sPath.max' => trans('validation.max', ['name' => '5秒動画パス']),
            // 5秒動画サムネイル画像名
            'video5sThumbName.required' => trans('validation.choice.required', ['name' => '5秒動画サムネイル']),
            'video5sThumbName.max' => trans('validation.max', ['name' => '5秒動画サムネイル画像名']),
            // 5秒動画サムネイル画像パス
            'video5sThumbPath.required' => trans('validation.choice.required', ['name' => '5秒動画サムネイル']),
            'video5sThumbPath.max' => trans('validation.max', ['name' => '5秒動画サムネイル画像パス']),
            // 5秒動画タイトル
            'video5sTitle.max' => trans('validation.max', ['name' => '5秒動画タイトル']),
            'video5sTitle.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '5秒動画タイトル']),
            // 10秒動画名
            'video10sName.required' => trans('validation.choice.required', ['name' => '10秒動画']),
            'video10sName.max' => trans('validation.max', ['name' => '10秒動画名']),
            // 10秒動画パス
            'video10sPath.required' => trans('validation.choice.required', ['name' => '10秒動画']),
            'video10sPath.max' => trans('validation.max', ['name' => '10秒動画パス']),
            // 10秒動画サムネイル画像名
            'video10sThumbName.required' => trans('validation.choice.required', ['name' => '10秒動画サムネイル']),
            'video10sThumbName.max' => trans('validation.max', ['name' => '10秒動画サムネイル画像名']),
            // 10秒動画サムネイル画像パス
            'video10sThumbPath.required' => trans('validation.choice.required', ['name' => '10秒動画サムネイル']),
            'video10sThumbPath.max' => trans('validation.max', ['name' => '10秒動画サムネイル画像パス']),
            // 10秒動画タイトル
            'video10sTitle.max' => trans('validation.max', ['name' => '10秒動画タイトル']),
            'video10sTitle.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '10秒動画タイトル']),
            // 15秒動画名
            'video15sName.required' => trans('validation.choice.required', ['name' => '15秒動画']),
            'video15sName.max' => trans('validation.max', ['name' => '15秒動画名']),
            // 15秒動画パス
            'video15sPath.required' => trans('validation.choice.required', ['name' => '15秒動画']),
            'video15sPath.max' => trans('validation.max', ['name' => '15秒動画パス']),
            // 15秒動画サムネイル画像名
            'video15sThumbName.required' => trans('validation.choice.required', ['name' => '15秒動画サムネイル']),
            'video15sThumbName.max' => trans('validation.max', ['name' => '15秒動画サムネイル画像名']),
            // 15秒動画サムネイル画像パス
            'video15sThumbPath.required' => trans('validation.choice.required', ['name' => '15秒動画サムネイル']),
            'video15sThumbPath.max' => trans('validation.max', ['name' => '15秒動画サムネイル画像パス']),
            // 15秒動画タイトル
            'video15sTitle.max' => trans('validation.max', ['name' => '15秒動画タイトル']),
            'video15sTitle.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '15秒動画タイトル']),
            // 当社の特徴 動画・画像名
            'featureNames.array' => trans('validation.array', ['name' => '動画・画像名']),
            'featureNames.*.max' => trans('validation.max', ['name' => '動画・画像名']),
            // 当社の特徴 動画・画像パス
            'featurePaths.array' => trans('validation.array', ['name' => '動画・画像パス']),
            'featurePaths.*.max' => trans('validation.max', ['name' => '動画・画像パス']),
            // 当社の特徴 タイトル
            'featureTitles.array' => trans('validation.array', ['name' => 'タイトル']),
            'featureTitles.*.max' => trans('validation.max', ['name' => 'タイトル']),
            'featureTitles.*.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'タイトル']),
            // 当社の特徴 説明
            'featureDescriptions.array' => trans('validation.array', ['name' => '説明文']),
            'featureDescriptions.*.max' => trans('validation.max', ['name' => '説明文']),
            'featureDescriptions.*halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => '説明文']),
            // ハッシュタグ
            'hashtag.required' => trans('validation.required', ['name' => 'ハッシュタグ']),
            'hashtag.max' => trans('validation.max', ['name' => 'ハッシュタグ']),
            'hashtag.halfwidth_kana_control' => trans('validation.other.halfwidth_kana_control', ['name' => 'ハッシュタグ']),
            'hashtag.input_hash' => trans('validation.other.input_hash',['name'=>'#']),
            // ハッシュタグカラー
            'hashTagColor.required' => trans('validation.choice.required', ['name' => 'ハッシュタグカラー']),
            'hashTagColor.integer' => trans('validation.choice.integer', ['name' => 'ハッシュタグカラー']),
            'hashTagColor.between' => trans('validation.choice.between', ['name' => 'ハッシュタグカラー']),
            // 募集タグ
            'recruitmentTargetYear.in' => trans('validation.choice.in', ['name' => '募集対象']),
            'recruitmentTargetThisYear.in' => trans('validation.choice.in', ['name' => '募集対象']),
            'recruitmentTargetIntern.in' => trans('validation.choice.in', ['name' => '募集対象']),
        ];

        Log::infoOut();

        return $messages;
    }
}
