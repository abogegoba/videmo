<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface VideoInterviewCompanyListSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface VideoInterviewCompanyListSearchExpressionBuilder
{
    /**
     * 会社ID
     *
     * @param null|string $companyId
     */
    public function setCompanyId(?string $companyId): void;
}