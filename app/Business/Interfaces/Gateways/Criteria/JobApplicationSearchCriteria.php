<?php

namespace App\Business\Interfaces\Gateways\Criteria;

use ReLab\Commons\Interfaces\Criteria;

/**
 * Interface JobApplicationSearchCriteria
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface JobApplicationSearchCriteria extends Criteria
{
    /**
     * ページャー
     *
     * @param $pager
     */
    public function setPager($pager): void;
}