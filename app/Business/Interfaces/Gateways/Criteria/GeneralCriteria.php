<?php

namespace App\Business\Interfaces\Gateways\Criteria;

use ReLab\Commons\Interfaces\Criteria;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface GeneralCriteria
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface GeneralCriteria extends Criteria
{
    /**
     * ページャー
     *
     * @param null|Pager $pager
     */
    public function setPager($pager): void;

    /**
     * オーダー
     *
     * @param array|null $orderBy
     */
    public function setOrderBy($orderBy): void;

    /**
     * グループ
     *
     * @param array|null $groupBy
     */
    public function setGroupBy($groupBy): void;
}