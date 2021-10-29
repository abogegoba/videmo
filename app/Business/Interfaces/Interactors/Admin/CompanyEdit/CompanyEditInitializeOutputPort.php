<?php

namespace App\Business\Interfaces\Interactors\Admin\CompanyEdit;

/**
 * Interface CompanyEditInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\CompanyEdit
 *
 * @property array $prefectureList 都道府県リスト
 * @property array $businessTypeList 業種リスト
 * @property array $accountList アカウントリスト
 * @property string $name 会社名
 * @property string $nameKana 会社名（かな）
 * @property string $zip 郵便番号
 * @property int $prefectures 都道府県
 * @property string $city 市区町村
 * @property string $room 建物名・階数など
 * @property int $industryCondition 業種
 * @property string $descriptionOfBusiness 事業内容
 * @property string $establishmentDate 設立
 * @property string $capital 資本金
 * @property string $payrollNumber 従業員
 * @property string $sales 売上高
 * @property string $representativePerson 代表者
 * @property string $exectiveOfficers 役員構成
 * @property string $establishment 事業所
 * @property string $affiliatedCompany 関連会社
 * @property string $qualification 登録・資格
 * @property string $homePageUrl ホームページURL
 * @property string $recruitmentUrl 採用ホームページ
 * @property string $mainClient 主要取引先
 * @property string $uploadedLogo 企業ロゴ
 * @property string $uploadedLogoName 企業ロゴ名
 * @property string $uploadedLogoPath 企業ロゴパス
 * @property string $picName 担当者名
 * @property string $picPhoneNumber 連絡先電話番号
 * @property string $picEmergencyPhoneNumber 緊急連絡先電話番号
 * @property string $picMailAddress 連絡先メールアドレス
 * @property string $displayImage 企業画像一覧表示
 * @property array $companyImages 企業画像
 * @property string[] $companyImageNames 企業画像名
 * @property string[] $companyImagePaths 企業画像パス
 * @property string $introductorySentence 企業紹介文
 * @property string $prVideo PR動画
 * @property string $prVideoName PR動画名
 * @property string $prVideoPath PR動画パス
 * @property string $video5s 5秒動画
 * @property string $video5sName 5秒動画名
 * @property string $video5sPath 5秒動画パス
 * @property string $video5sThumb 5秒動画サムネイル画像
 * @property string $video5sThumbName 5秒動画サムネイル画像名
 * @property string $video5sThumbPath 5秒動画サムネイル画像パス
 * @property string $video10s 10秒動画
 * @property string $video10sName 10秒動画名
 * @property string $video10sPath 10秒動画パス
 * @property string $video10sThumb 10秒動画サムネイル画像
 * @property string $video10sThumbName 10秒動画サムネイル画像名
 * @property string $video10sThumbPath 10秒動画サムネイル画像パス
 * @property string $video15s 15秒動画
 * @property string $video15sName 15秒動画名
 * @property string $video15sPath 15秒動画パス
 * @property string $video15sThumb 15秒動画サムネイル画像
 * @property string $video15sThumbName 15秒動画サムネイル画像名
 * @property string $video15sThumbPath 15秒動画サムネイル画像パス
 * @property array $features 当社の特徴
 * @property array $featureNames 当社の特徴ファイル名
 * @property array $featurePaths 当社の特徴ファイルパス
 * @property string[] $featureTitles 当社の特徴ファイルタイトル
 * @property string[] $featureDescriptions 当社の特徴ファイル説明
 * @property string $hashtag ハッシュタグ
 * @property int $hashTagColor ハッシュタグカラー
 * @property boolean $recruitmentTargetYear 募集対象年（今年）
 * @property boolean $recruitmentTargetThisYear 募集対象年（来年）
 * @property boolean $recruitmentTargetIntern インターン
 * @property boolean $managementMemo 管理メモ
 * @property int $status ステータス
 * @property int $jobApplicationAvailableNumber 求人掲載可能数
 */
interface CompanyEditInitializeOutputPort extends CompanyEditInitializeInputPort
{
}
