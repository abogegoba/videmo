<?php

namespace ReLab\Commons\Wrappers;

use ReLab\Commons\Interfaces\Criteria;

/**
 * Class CriteriaFactory
 *
 * @package ReLab\Commons\Wrappers
 */
abstract class CriteriaFactory
{
    /**
     * @var CriteriaFactory
     */
    private static $criteriaFactory;

    /**
     * 実装する
     *
     * @param CriteriaFactory $criteriaFactory
     */
    public static function implement(CriteriaFactory $criteriaFactory): void
    {
        self::$criteriaFactory = $criteriaFactory;
    }

    /**
     * インスタンスを取得する
     *
     * @return null|CriteriaFactory
     */
    public static function getInstance(): ?CriteriaFactory
    {
        return self::$criteriaFactory;
    }

    /**
     * 指定されたインターフェースに該当するCriteriaのインスタンスを作成する
     *
     * @param string $criteriaInterface
     * @param null|string $expressionBuilderInterface
     * @param array|\ArrayObject|object|null $expressionBuilderValues
     * @param array|\ArrayObject|object|null $criteriaOptions
     * @return Criteria
     */
    abstract public function create(string $criteriaInterface, ?string $expressionBuilderInterface = null, $expressionBuilderValues = null, $criteriaOptions = null);
}
