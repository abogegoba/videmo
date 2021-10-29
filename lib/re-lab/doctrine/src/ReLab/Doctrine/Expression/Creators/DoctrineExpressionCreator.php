<?php

namespace ReLab\Doctrine\Expression\Creators;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Func;

/**
 * Trait DoctrineExpressionCreator
 *
 * @package ReLab\Doctrine\Expression\Creators
 */
trait DoctrineExpressionCreator
{
    /**
     * INNER JOIN
     *
     * @var array
     */
    private $innerJoins = [];

    /**
     * LEFT JOIN
     *
     * @var array
     */
    private $leftJoins = [];

    /**
     * パラメータキーカウント一覧
     *
     * @var string[]
     */
    private $parameterKeyCount = [];

    /**
     * パラメータ一覧
     *
     * @var array
     */
    private $parameters = [];

    /**
     * 指定されたフィールド名をエイリアス付きのフィールド名に変換する
     *
     * @param string $field
     * @param bool $joinLeft
     * @param null|string $parentAlias
     * @return string
     */
    public function convertAliasField(string $field, bool $joinLeft = false, ?string $parentAlias = null): string
    {
        // 拡張関数を使用している場合はそのままの文字列を返す
        if(preg_match("@^.+?\(.+\)$@",$field)){
            return $field;
        }

        $fields = explode(".", $field);
        $count = count($fields);
        if ($count == 1) {
            return $this->entityAlias() . "." . $field;
        } else {
            if (isset($parentAlias)) {
                $field = $parentAlias . "." . $fields[0];
                $alias = $parentAlias . "_" . $fields[0];
            } else {
                $field = $this->entityAlias() . "." . $fields[0];
                $alias = $fields[0];
            }
            if (!isset($this->innerJoins[$alias]) && !isset($this->leftJoins[$alias])) {
                if ($joinLeft) {
                    $this->leftJoins[$alias] = $field;
                } else {
                    $this->innerJoins[$alias] = $field;
                }
            }
            if ($count == 2) {
                return $alias . "." . $fields[1];
            } else {
                unset($fields[0]);
                return $this->convertAliasField(implode(".", $fields), $joinLeft, $alias);
            }
        }
    }

    /**
     * 指定されたフィールド名をパラメータキーに変換する
     *
     * @param string|string[] $field
     * @return string
     */
    public function convertParameterKey($field): string
    {
        if (is_array($field)) {
            $field = implode("_", $field);
        }
        // パラメータキー指定に使用不可な文字列を「_」に変換する
        $field = preg_replace("@\.|\(|\)|,|'|\"@", "_", $field);
        if (isset($this->parameterKeyCount[$field])) {
            $this->parameterKeyCount[$field]++;
        } else {
            $this->parameterKeyCount[$field] = 1;
        }
        return $field . "_" . $this->parameterKeyCount[$field];
    }

    /**
     * フィールド名からパラメータを設定する
     *
     * 帰り値として変換されたパラメータキーを返却します。
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return null|string
     */
    public function setParameterFromField($field, $value): string
    {
        $parameterKey = $this->convertParameterKey($field);
        $this->parameters[$parameterKey] = $value;
        return $parameterKey;
    }

    /**
     * INNER JOIN 取得
     *
     * @return array
     */
    public function getInnerJoins(): array
    {
        return $this->innerJoins;
    }

    /**
     * LEFT JOIN 取得
     *
     * @return array
     */
    public function getLeftJoins(): array
    {
        return $this->leftJoins;
    }

    /**
     * パラメータ取得
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Entity alias
     *
     * @return string
     */
    abstract public function entityAlias(): string;

    /**
     * Doctrine expression builder
     *
     * @return Expr
     */
    abstract protected function expr(): Expr;

    /**
     * 一致条件
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return \Doctrine\ORM\Query\Expr\Comparison|mixed|null
     */
    public function eq($field, $value)
    {
        if (isset($value)) {
            if (is_array($field)) {
                $aliasField = $this->concat($field);
            } else {
                $aliasField = $this->convertAliasField($field);
            }
            return $this->expr()->eq($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * 不一致条件
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return \Doctrine\ORM\Query\Expr\Comparison|mixed|null
     */
    public function neq($field, $value)
    {
        if (isset($value)) {
            if (is_array($field)) {
                $aliasField = $this->concat($field);
            } else {
                $aliasField = $this->convertAliasField($field);
            }
            return $this->expr()->neq($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * 未満条件
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return \Doctrine\ORM\Query\Expr\Comparison|mixed|null
     */
    public function lt($field, $value)
    {
        if (isset($value)) {
            if (is_array($field)) {
                $aliasField = $this->concat($field);
            } else {
                $aliasField = $this->convertAliasField($field);
            }
            return $this->expr()->lt($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * 以下条件
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return \Doctrine\ORM\Query\Expr\Comparison|mixed|null
     */
    public function lte($field, $value)
    {
        if (isset($value)) {
            if (is_array($field)) {
                $aliasField = $this->concat($field);
            } else {
                $aliasField = $this->convertAliasField($field);
            }
            return $this->expr()->lte($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * 超条件
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return \Doctrine\ORM\Query\Expr\Comparison|mixed|null
     */
    public function gt($field, $value)
    {
        if (isset($value)) {
            if (is_array($field)) {
                $aliasField = $this->concat($field);
            } else {
                $aliasField = $this->convertAliasField($field);
            }
            return $this->expr()->gt($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * 以上条件
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return \Doctrine\ORM\Query\Expr\Comparison|mixed|null
     */
    public function gte($field, $value)
    {
        if (isset($value)) {
            if (is_array($field)) {
                $aliasField = $this->concat($field);
            } else {
                $aliasField = $this->convertAliasField($field);
            }
            return $this->expr()->gte($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * LIKE一致条件
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return \Doctrine\ORM\Query\Expr\Comparison|mixed|null
     */
    public function like($field, $value)
    {
        if (isset($value)) {
            if (is_array($field)) {
                $aliasField = $this->concat($field);
            } else {
                $aliasField = $this->convertAliasField($field);
            }
            return $this->expr()->like($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * LIKE不一致条件
     *
     * @param string|string[] $field
     * @param mixed $value
     * @return \Doctrine\ORM\Query\Expr\Comparison|mixed|null
     */
    public function notLike($field, $value)
    {
        if (isset($value)) {
            if (is_array($field)) {
                $aliasField = $this->concat($field);
            } else {
                $aliasField = $this->convertAliasField($field);
            }
            return $this->expr()->notLike($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * IN条件
     *
     * @param string $field
     * @param $value
     * @return \Doctrine\ORM\Query\Expr\Func|null
     */
    public function in(string $field, $value)
    {
        if (isset($value)) {
            $aliasField = $this->convertAliasField($field);
            return $this->expr()->in($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * NOT IN条件
     *
     * @param string $field
     * @param $value
     * @return \Doctrine\ORM\Query\Expr\Func|null
     */
    public function notIn(string $field, $value)
    {
        if (isset($value)) {
            $aliasField = $this->convertAliasField($field);
            return $this->expr()->notIn($aliasField, ":" . $this->setParameterFromField($field, $value));
        } else {
            return null;
        }
    }

    /**
     * NULL一致条件
     *
     * @param string $field
     * @return string
     */
    public function isNull(string $field)
    {
        $aliasField = $this->convertAliasField($field);
        return $this->expr()->isNull($aliasField);
    }

    /**
     * NULL非一致条件
     *
     * @param string $field
     * @return string
     */
    public function isNotNull(string $field)
    {
        $aliasField = $this->convertAliasField($field);
        return $this->expr()->isNotNull($aliasField);
    }

    /**
     * フィールド結合
     *
     * @param string[] $fields
     * @return Func
     */
    public function concat(array $fields)
    {
        $aliasFields = [];
        foreach ($fields as $field) {
            $aliasFields[] = $this->convertAliasField($field, true);
        }
        return new Func('CONCAT', $aliasFields);
    }

    /**
     * AND条件
     *
     * @param array $expressions
     * @return \Doctrine\ORM\Query\Expr\Andx|null
     */
    public function and (array $expressions)
    {
        $expressionsWithOutNull = [];
        foreach ($expressions as $expression) {
            if (isset($expression)) {
                $expressionsWithOutNull[] = $expression;
            }
        }
        if (!empty($expressionsWithOutNull)) {
            return new \Doctrine\ORM\Query\Expr\Andx($expressionsWithOutNull);
        } else {
            return null;
        }
    }

    /**
     * OR条件
     *
     * @param array $expressions
     * @return \Doctrine\ORM\Query\Expr\Orx|null
     */
    public function or (array $expressions)
    {
        $expressionsWithOutNull = [];
        foreach ($expressions as $expression) {
            if (isset($expression)) {
                $expressionsWithOutNull[] = $expression;
            }
        }
        if (!empty($expressionsWithOutNull)) {
            return new \Doctrine\ORM\Query\Expr\Orx($expressionsWithOutNull);
        } else {
            return null;
        }
    }
}