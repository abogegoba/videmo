<?php

namespace App\Business\Interfaces\Gateways\Criteria;

use ReLab\Commons\Interfaces\Criteria;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface MemberSearchCriteria
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface MemberSearchCriteria extends Criteria
{
    /**
     * ページャー
     *
     * @param null|Pager $pager
     */
    public function setPager($pager): void;
}