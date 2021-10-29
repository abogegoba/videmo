<?php

namespace ReLab\Commons\Interfaces;

/**
 * Interface Criteria
 *
 * @package ReLab\Commons\Interfaces
 */
interface Criteria
{
    /**
     * オーダー：昇順
     *
     * @var string
     */
    const ORDER_ASC = "ASC";

    /**
     * オーダー：降順
     *
     * @var string
     */
    const ORDER_DESC = "DESC";

    /**
     * 条件作成
     *
     * @return ExpressionBuilder|null
     */
    public function expressionBuilder();

    /**
     * ページャー
     *
     * @return Pager|null
     */
    public function pager();

    /**
     * オーダー
     *
     * @return array|null
     */
    public function orderBy();

    /**
     * グループ
     *
     * @return array|null
     */
    public function groupBy();

    /**
     * 悲観ロック
     *
     * @return bool|null
     */
    public function forUpdate();
}