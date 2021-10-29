<?php

namespace ReLab\Domain\VOs\Traits;

/**
 * Trait Identifiable
 *
 * @package ReLab\Domain\VOs\Traits
 */
trait Identifiable
{
    /**
     * @var int|string
     */
    private $identifier;

    /**
     * KeyValue constructor.
     *
     * @param int|string $identifier 識別子
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * 識別子を取得する
     *
     * @return int|string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * 識別子を比較する
     *
     * @param int|string $identifier
     * @return bool
     */
    public function identifierIs($identifier)
    {
        return $this->identifier == $identifier;
    }

    /**
     * 識別子に該当するラベルを取得する
     *
     * @return null|string
     */
    public function toString(): ?string
    {
        $labels = self::labels();
        if (isset($labels[$this->identifier])) {
            return $labels[$this->identifier];
        }
        return null;
    }

    /**
     * 識別ラベル一覧を取得する
     *
     * @return array
     */
    abstract public static function labels(): array;
}