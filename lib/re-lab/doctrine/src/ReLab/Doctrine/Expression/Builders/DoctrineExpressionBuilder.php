<?php

namespace ReLab\Doctrine\Expression\Builders;

use Doctrine\ORM\Query\Expr;
use ReLab\Commons\Interfaces\ExpressionBuilder;
use ReLab\Doctrine\Expression\Creators\DoctrineExpressionCreator;

/**
 * Class DoctrineExpressionBuilder
 *
 * @package ReLab\Doctrine\Expression\Builders
 */
class DoctrineExpressionBuilder implements ExpressionBuilder
{
    use DoctrineExpressionCreator;

    /**
     * Default entity alias
     *
     * @var string
     */
    const DEFAULT_ENTITY_ALIAS = "DoctrineEntity";

    /**
     * Doctrine expression builder.
     *
     * @var Expr
     */
    private static $expr = null;

    /**
     * 条件作成実行
     *
     * @return mixed|null
     */
    public function build()
    {
        return null;
    }

    /**
     * Entity alias
     *
     * @return string
     */
    public function entityAlias(): string
    {
        return self::DEFAULT_ENTITY_ALIAS;
    }

    /**
     * Doctrine expression builder
     *
     * @return Expr
     */
    protected function expr(): Expr
    {
        if (!isset(self::$expr)) {
            self::$expr = new Expr();
        }
        return self::$expr;
    }

    /**
     * スペース除去
     *
     * @param $string
     * @return null|string
     */
    protected function cutSpace($string)
    {
        $nonSpaceString = null;
        if (isset($string)) {
            $nonSpaceString = preg_replace('/ /', '', preg_replace('/　/', ' ', $string));
        }
        return $nonSpaceString;
    }
}