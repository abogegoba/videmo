<?php


namespace App\Business\Interfaces\Gateways\Criteria;


use ReLab\Commons\Interfaces\Criteria;
use ReLab\Commons\Interfaces\Pager;

/**
 * Interface ModelSentenceListSearchCriteria
 *
 * @package App\Business\Interfaces\Gateways\Criteria
 */
interface ModelSentenceListSearchCriteria extends Criteria
{
    /**
     * ページャー
     *
     * @param null|Pager $pager
     */
    public function setPager($pager): void;
}