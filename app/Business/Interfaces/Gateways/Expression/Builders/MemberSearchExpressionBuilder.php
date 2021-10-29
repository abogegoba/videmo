<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface MemberSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface MemberSearchExpressionBuilder
{
    /**
     * キーワード検索
     *
     * @param null|string $keywordCondition
     */
    public function setKeywordCondition(?string $keywordCondition): void;

    /**
     * 学部系統
     *
     * @param null|int $undergraduateCourseCondition
     */
    public function setUndergraduateCourseCondition(?int $undergraduateCourseCondition): void;

    /**
     * 希望業種
     *
     * @param null|int $industryCondition
     */
    public function setIndustryCondition(?int $industryCondition): void;

    /**
     * 希望勤務地
     *
     * @param null|int $areaCondition
     */
    public function setAreaCondition(?int $areaCondition): void;

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

    /**
     * インターン希望者
     *
     * @param null|int $isInternApplicantCondition
     */
    public function setIsInternApplicantCondition(?int $isInternApplicantCondition): void;

    /**
     * 体育会系所属
     *
     * @param null|int $isBelongsAthleticClubCondition
     */
    public function setIsBelongsAthleticClubCondition(?int $isBelongsAthleticClubCondition): void;

    /**
     * 国籍
     *
     * @param null|int $countryCondition
     */
    public function setCountryCondition(?int $countryCondition): void;
}
