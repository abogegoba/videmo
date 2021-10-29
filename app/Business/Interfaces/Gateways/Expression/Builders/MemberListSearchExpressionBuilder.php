<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface MemberListSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface MemberListSearchExpressionBuilder
{
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
     * 学校名
     *
     * @param null|string $schoolName
     */
    public function setSchoolName(?string $schoolName): void;

    /**
     * 連絡先電話番号
     *
     * @param null|string $phoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): void;

    /**
     * 卒業年
     *
     * @param null|string $graduationPeriodYear
     */
    public function setGraduationPeriodYear(?string $graduationPeriodYear): void;

    /**
     * 卒業月
     *
     * @param null|string $graduationPeriodMonth
     */
    public function setGraduationPeriodMonth(?string $graduationPeriodMonth): void;
}