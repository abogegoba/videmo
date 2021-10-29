<?php

namespace App\Business\Interfaces\Gateways\Criteria;

/**
 * Interface CompanySearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface CompanySearchExpressionBuilder
{
    /**
     * 企業名
     *
     * @param null|string $companyNameCondition
     */
    public function setCompanyNameCondition(?string $companyNameCondition): void;

    /**
     * 業種
     *
     * @param null|string $industryCondition
     */
    public function setIndustryCondition(?string $industryCondition): void;

    /**
     * 職種
     *
     * @param null|string $jobTypeCondition
     */
    public function setJobTypeCondition(?string $jobTypeCondition): void;

    /**
     * エリア
     *
     * @param null|string $areaCondition
     */
    public function setAreaCondition(?string $areaCondition): void;

    /**
     * 募集対象（今年度）
     *
     * @param null|int $recruitmentTargetConditionThisYear
     */
    public function setRecruitmentTargetConditionThisYear(?int $recruitmentTargetConditionThisYear): void;

    /**
     * 募集対象（来年度）
     *
     * @param null|int $recruitmentTargetConditionNextYear
     */
    public function setRecruitmentTargetConditionNextYear(?int $recruitmentTargetConditionNextYear): void;

    /**
     * 募集対象（インターン）
     *
     * @param null|int $recruitmentTargetConditionIntern
     */
    public function setRecruitmentTargetConditionIntern(?int $recruitmentTargetConditionIntern): void;

    /**
     * @param int $status
     */
    public function setStatus(int $status): void;
}