<?php


namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface ModelSentenceListSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface ModelSentenceListSearchExpressionBuilder
{
    /**
     * 例文名
     *
     * @param string|null $modelSentenceName
     */
    public function setModelSentenceName(?string $modelSentenceName): void;

    /**
     * 例文種別
     *
     * @param array[]|null $modelSentenceType
     */
    public function setModelSentenceType($modelSentenceType): void;
}