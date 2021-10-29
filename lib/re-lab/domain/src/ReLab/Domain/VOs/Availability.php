<?php

namespace ReLab\Domain\VOs;

use ReLab\Domain\VOs\Traits\Identifiable;

/**
 * Class Activity
 *
 * @package ReLab\Domain\VOs
 */
class Availability
{
    use Identifiable;

    /**
     * 有効
     *
     * @var bool
     */
    const ENABLE = 1;

    /**
     * 無効
     *
     * @var bool
     */
    const DISABLE = 0;

    /**
     * ラベル
     *
     * @var array
     */
    const LABELS = [
        self::ENABLE => "有効",
        self::DISABLE => "無効",
    ];

    /**
     * 識別ラベル一覧を取得する
     *
     * @return array
     */
    public static function labels(): array
    {
        return self::LABELS;
    }
}