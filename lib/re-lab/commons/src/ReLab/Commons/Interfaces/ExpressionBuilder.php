<?php

namespace ReLab\Commons\Interfaces;

/**
 * Interface ExpressionBuilder
 *
 * @package ReLab\Commons\Interfaces
 */
interface ExpressionBuilder
{
    /**
     * 条件作成実行
     *
     * @return mixed|null
     */
    public function build();
}