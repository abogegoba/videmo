<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface VideoInterviewMemberListSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface VideoInterviewMemberListSearchExpressionBuilder
{
    /**
     * 会員ID
     *
     * @param null|string $memberId
     */
    public function setMemberId(?string $memberId): void;
}