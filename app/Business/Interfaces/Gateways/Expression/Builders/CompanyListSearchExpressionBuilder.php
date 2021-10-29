<?php

namespace App\Business\Interfaces\Gateways\Criteria;

/**
 * Interface CompanyListSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface CompanyListSearchExpressionBuilder
{
    /**
     * 企業名
     *
     * @param null|string $companyName
     */
    public function setCompanyName(?string $companyName): void;

    /**
     * 企業名かな
     *
     * @param null|string $companyNameKana
     */
    public function setCompanyNameKana(?string $companyNameKana): void;

    /**
     * 企業ステータス
     *
     * @param null|int $companyStatusList
     */
    public function setCompanyStatusList(?int $companyStatusList): void;

    /**
     * 求人枠最小数
     *
     * @param null|int $minJobApplicationAvailableNumber
     */
    public function setMinJobApplicationAvailableNumber(?int $minJobApplicationAvailableNumber): void;

    /**
     * 求人枠最大数
     *
     * @param null|int $maxJobApplicationAvailableNumber
     */
    public function setMaxJobApplicationAvailableNumber(?int $maxJobApplicationAvailableNumber): void;
}