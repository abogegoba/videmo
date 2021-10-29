<?php

namespace App\Business\Interfaces\Gateways\Criteria;

use ReLab\Commons\Interfaces\Criteria;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface VideoCallHistoryCompanyListSearchCriteria
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface VideoCallHistoryCompanyListSearchCriteria extends Criteria
{
    /**
     * ページャー
     *
     * @param null|Pager $pager
     */
    public function setPager($pager): void;
}