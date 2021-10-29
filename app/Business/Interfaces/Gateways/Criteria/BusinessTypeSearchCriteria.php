<?php

namespace App\Business\Interfaces\Gateways\Criteria;

use ReLab\Commons\Interfaces\Criteria;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface CompanySearchCriteria
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface CompanySearchCriteria extends Criteria
{
    /**
     * ページャー
     *
     * @param null|Pager $pager
     */
    public function setPager($pager): void;
}