<?php

namespace App\Business\Interfaces\Interactors\Client\MessageDetail;

/**
 * Interface MessageDetailInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberEntry
 *
 * @property int $loggedInUserAccountId ログインしているユーザアカウントID
 * @property string $privateImage プライベート写真
 * @property string $idImage 証明写真
 * @property string $memberName 会社名
 * @property string $companyLogo 企業ロゴ
 * @property array $exchangeMessageList メッセージリスト
 * @property int $memberUserAccountId 会員ユーザーアカウントID
 * @property string $studentDetailUrl 学生詳細URL
 * @property string $videoInterviewCancelUrl 面接予約詳細URL
 * @property string $videoInterviewEntryUrl 面接予約登録URL
 * @property array $modelSentenceNameList 例文名リスト
 * @property array $modelSentenceContentList 例文本文リスト
 */
interface MessageDetailInitializeOutputPort
{
}