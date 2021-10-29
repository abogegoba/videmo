<?php

namespace ReLab\Doctrine\Expression\Builders;

/**
 * Class GeneralDoctrineExpressionBuilder
 *
 * @package ReLab\Doctrine\Expression\Builders
 */
class GeneralDoctrineExpressionBuilder extends DoctrineExpressionBuilder
{
    /**
     * 値一式
     *
     * @var array
     */
    private $values = [];

    /**
     * 値設定
     *
     * @param string|string[] $field
     * @param mixed $value
     */
    public function setValue($field, $value): void
    {
        $this->values[$field] = $value;
    }

    /**
     * 条件作成実行
     *
     * @return \Doctrine\ORM\Query\Expr\Andx|mixed|null
     */
    public function build()
    {
        $expressions = [];
        foreach ($this->values as $field => $value) {
            if (is_array($value)) {
                $expressions[] = $this->in($field, $value);
            } else if ($value instanceof \ArrayObject) {
                $expressions[] = $this->in($field, $value->getArrayCopy());
            } else {
                $expressions[] = $this->eq($field, $value);
            }
        }
        if (count($expressions) > 0) {
            return $this->and($expressions);
        } else {
            return null;
        }
    }
}