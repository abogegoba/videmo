<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface GeneralExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface GeneralExpressionBuilder
{
    /**
     * 値設定
     *
     * @param string|string[] $field
     * @param mixed $value
     */
    public function setValue($field, $value): void;
}