<?php

namespace App\Business\Interfaces\Interactors\Front\MessageDetail;

/**
 * Interface MessageDetailInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 *
 * @property bool $canSendMessageToCompany 企業へメッセージを送信できるか判断
 * @property int $loggedInUserAccountId ログインしているユーザアカウントID
 * @property string $companyLogoFilePath 企業ロゴファイルパス
 * @property string $name 会社名
 * @property string $companyLogo 企業ロゴ
 * @property array $exchangeMessageList メッセージリスト
 * @property array $companyUserAccountId 企業ユーザーアカウントID
 * @property bool $requestFlg 面接予約依頼フラグ
 * @property string $request 面接予約依頼文
 * @property string $companyDetailUrl 企業詳細URL
 * @property array $modelSentenceNameList 例文名リスト
 * @property array $modelSentenceContentList 例文本文リスト
 */
interface MessageDetailInitializeOutputPort
{
}