<?php

namespace App\Business\Interfaces\Interactors\Front\CompanySearch;

/**
 * Interface StudentSearchInitializeInputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\CompanySearch
 * @property bool $isCondition 検索条件有無
 * @property string $companyNameCondition 検索条件（企業名）
 * @property int $industryCondition 検索条件（業種）
 * @property int $jobTypeCondition 検索条件（職種）
 * @property int $areaCondition 検索条件（エリア）
 * @property int $recruitmentTargetConditionThisYear 募集対象（今年度）
 * @property int $recruitmentTargetConditionNextYear 募集対象（来年度）
 * @property int $recruitmentTargetConditionIntern 募集対象（インターン）
 */
interface CompanySearchInitializeInputPort
{
}