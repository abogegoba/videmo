<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface InterviewAppointmentListSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface InterviewAppointmentListSearchExpressionBuilder
{
    /**
     * 会社名
     *
     * @param null|string $companyName
     */
    public function setCompanyName(?string $companyName): void;

    /**
     * 会社名かな
     *
     * @param null|string $companyNameKana
     */
    public function setCompanyNameKana(?string $companyNameKana): void;

    /**
     * 会員名
     *
     * @param null|string $memberName
     */
    public function setMemberName(?string $memberName): void;

    /**
     * 会員名かな
     *
     * @param null|string $memberNameKana
     */
    public function setMemberNameKana(?string $memberNameKana): void;

    /**
     * ステータス
     *
     * @param null|int $interviewAppointmentStatus
     */
    public function setInterviewAppointmentStatus(?int $interviewAppointmentStatus): void;
}