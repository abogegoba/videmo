<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileAddressEdit;

/**
 * Interface CompanyRecruitingEditInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileAddressEdit
 * @property string $zipCode 郵便番号
 * @property int $prefecture 都道府県
 * @property string $city 市区町村
 * @property string $blockNumber 番地・建物名・部屋番号など
 * @property string $phoneNumber 電話番号
 * @property array $prefectureList 都道府県リスト
 * @property int $country 現住所 (都道府県)
 * @property array $overseasList 現住所 (都道府県)
 */
interface ProfileAddressEditInitializeOutputPort
{
}
