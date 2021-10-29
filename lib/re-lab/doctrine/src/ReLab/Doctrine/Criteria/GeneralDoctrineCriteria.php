<?php

namespace ReLab\Doctrine\Criteria;

use ReLab\Commons\Interfaces\Pager;
use ReLab\Doctrine\Expression\Builders\GeneralDoctrineExpressionBuilder;

/**
 * Class GeneralDoctrineCriteria
 *
 * @package ReLab\Doctrine\Criteria
 */
class GeneralDoctrineCriteria extends DoctrineCriteria
{
    /**
     * GeneralDoctrineCriteria constructor.
     *
     * @param GeneralDoctrineExpressionBuilder|null $expressionBuilder
     */
    public function __construct(GeneralDoctrineExpressionBuilder $expressionBuilder = null)
    {
        if (isset($expressionBuilder)) {
            parent::__construct($expressionBuilder);
        } else {
            parent::__construct(new GeneralDoctrineExpressionBuilder());
        }
    }

    /**
     * ページャー
     *
     * @param Pager $pager
     */
    public function setPager($pager)
    {
        $this->pager = $pager;
    }

    /**
     * オーダー
     *
     * @param array $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * グループ
     *
     * @param array $groupBy
     */
    public function setGroupBy($groupBy)
    {
        $this->groupBy = $groupBy;
    }

    /**
     * 悲観ロック
     *
     * @param bool $forUpdate
     */
    public function setForUpdate($forUpdate)
    {
        $this->forUpdate = $forUpdate;
    }
}