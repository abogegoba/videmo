<?php


namespace App\Business\Interfaces\Gateways\Criteria;

use ReLab\Commons\Interfaces\Criteria;

/**
 * Interface AdminMessageListSearchCriteria
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface AdminMessageListSearchCriteria extends Criteria
{
    public function setPager($pager): void;
}