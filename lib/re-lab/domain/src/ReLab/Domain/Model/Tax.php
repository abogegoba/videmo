<?php

namespace ReLab\Domain\Model;

/**
 * Class Tax
 *
 * @package ReLab\Domain\Model
 */
class Tax
{
    /**
     * 消費税率
     *
     * @var float
     */
    const RATE = 0.08;

    /**
     * 消費税計算：四捨五入
     *
     * @var int
     */
    const CALCULATE_TYPE_ROUND = 1;

    /**
     * 消費税計算：小数点以下切り捨て
     *
     * @var int
     */
    const CALCULATE_TYPE_FLOOR = 2;

    /**
     * 消費税計算：小数点以下切り上げ
     *
     * @var int
     */
    const CALCULATE_TYPE_CEIL = 3;

    /**
     * 消費税額を計算する
     *
     * @param float $amount
     * @param int|null $calculateType
     * @return float
     */
    public static function calculate(float $amount, ?int $calculateType = null): float
    {
        $result = $amount * self::RATE;
        if (isset($calculateType)) {
            switch ($calculateType) {
                case self::CALCULATE_TYPE_ROUND:
                    $result = round($result);
                    break;
                case self::CALCULATE_TYPE_FLOOR:
                    $result = floor($result);
                    break;
                case self::CALCULATE_TYPE_CEIL:
                    $result = ceil($result);
                    break;
            }
        }
        return $result;
    }
}
