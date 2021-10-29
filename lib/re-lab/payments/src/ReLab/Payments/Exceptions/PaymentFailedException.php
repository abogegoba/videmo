<?php

namespace ReLab\Payments\Exceptions;

use ReLab\Commons\Exceptions\BusinessException;

/**
 * Class PaymentFailedException
 *
 * 支払い失敗例外
 *
 * @package ReLab\Payments\Exceptions
 */
class PaymentFailedException extends BusinessException
{
    /**
     * 本例外のベースとなる例外キーを取得する
     *
     * @return string
     */
    protected function getBaseKey(): string
    {
        $key = parent::getBaseKey();
        return $key . ".payment_failed";
    }

    /**
     * 想定外例外
     *
     * @return PaymentFailedException
     */
    public static function unexpected(): PaymentFailedException
    {
        return new PaymentFailedException("unexpected");
    }
}