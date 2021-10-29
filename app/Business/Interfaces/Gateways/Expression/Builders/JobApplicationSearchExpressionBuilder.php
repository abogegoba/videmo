<?php


namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface JobApplicationSearchExpressionBuilder
 *
 * @package App\Adapters\Gateways\Expression\Builders
 */
interface JobApplicationSearchExpressionBuilder
{
    /**
     * 会社名
     *
     * @param string|null $companyName
     */
    public function setCompanyName(?string $companyName): void;

    /**
     * 会社名かな
     *
     * @param string|null $companyNameKana
     */
    public function setCompanyNameKana(?string $companyNameKana): void;

    /**
     * ステータス
     *
     * @param array[]|null $status
     */
    public function setStatus($status): void;

    /**
     * 勤務地域
     *
     * @param string|null $area
     */
    public function setArea(?string $area): void;
}