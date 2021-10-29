<?php

namespace ReLab\Doctrine\Criteria;

use ReLab\Commons\Interfaces\Criteria;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Doctrine\Expression\Builders\DoctrineExpressionBuilder;

/**
 * Class DoctrineCriteria
 *
 * @package ReLab\Doctrine\Criteria
 */
abstract class DoctrineCriteria implements Criteria
{
    /**
     * @var DoctrineExpressionBuilder
     */
    protected $expressionBuilder;

    /**
     * ページャー
     *
     * @var Pager|null
     */
    protected $pager;

    /**
     * オーダー
     *
     * @var array|null
     */
    protected $orderBy;

    /**
     * グループ
     *
     * @var array|null
     */
    protected $groupBy;

    /**
     * 悲観ロック
     *
     * @var bool|null
     */
    protected $forUpdate;

    /**
     * DoctrineCriteria constructor.
     *
     * @param DoctrineExpressionBuilder|null $expressionBuilder
     */
    public function __construct(DoctrineExpressionBuilder $expressionBuilder = null)
    {
        if (isset($expressionBuilder)) {
            $this->expressionBuilder = $expressionBuilder;
        } else {
            $this->expressionBuilder = new DoctrineExpressionBuilder();
        }
    }

    /**
     * @param DoctrineExpressionBuilder $expressionBuilder
     */
    public function setExpressionBuilder(DoctrineExpressionBuilder $expressionBuilder)
    {
        $this->expressionBuilder = $expressionBuilder;
    }

    /**
     * @return DoctrineExpressionBuilder
     */
    public function expressionBuilder()
    {
        return $this->expressionBuilder;
    }

    /**
     * ページャー
     *
     * @return Pager|null
     */
    public function pager()
    {
        return $this->pager;
    }

    /**
     * オーダー
     *
     * @return array|null
     */
    public function orderBy()
    {
        return $this->orderBy;
    }

    /**
     * グループ
     *
     * @return array|null
     */
    public function groupBy()
    {
        return $this->groupBy;
    }

    /**
     * 悲観ロック
     *
     * @return bool|null
     */
    public function forUpdate()
    {
        return $this->forUpdate;
    }
}