<?php

namespace App\Business\Interfaces\Gateways\Criteria;

use ReLab\Commons\Interfaces\Criteria;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface VideoCallHistoryListSearchCriteria
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface VideoCallHistoryListSearchCriteria extends Criteria
{
    /**
     * ページャー
     *
     * @param null|Pager $pager
     */
    public function setPager($pager): void;
}