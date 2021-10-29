<?php


namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface AdminMessageListSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface AdminMessageListSearchExpressionBuilder
{
    /**
     * 会社名
     *
     * @param string|null $companyName
     */
    public function setCompanyName(?string $companyName): void;

    /**
     * 会員名
     *
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * ステータス
     *
     * @param array[]|null $status
     */
    public function setStatus($status): void;
}