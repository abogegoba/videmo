<?php

namespace ReLab\Payments\Interfaces\Acceptors;

/**
 * Interface CreditCardPaymentAcceptor
 *
 * クレジットカード決済受付
 *
 * @package ReLab\Payments\Interfaces\Acceptors
 */
interface CreditCardPaymentAcceptor extends PaymentAcceptor
{
    /**
     * クレジットカード支払方法：一括
     *
     * @var int
     */
    const PAYMENT_METHOD_BULK = 1;

    /**
     * クレジットカード支払方法：分割
     *
     * @var int
     */
    const PAYMENT_METHOD_SPLIT = 2;

    /**
     * クレジットカード支払方法：ボーナス一括
     *
     * @var int
     */
    const PAYMENT_METHOD_BONUS_BULK = 3;

    /**
     * クレジットカード支払方法：ボーナス分割
     *
     * @var int
     */
    const PAYMENT_METHOD_BONUS_SPLIT = 4;

    /**
     * クレジットカード支払方法：リボ払い
     *
     * @var int
     */
    const PAYMENT_METHOD_REVOLVING = 5;

    /**
     * CreditCardPaymentAcceptor：クレジットカードトークン取得
     *
     * @return null|string
     */
    public function getCreditCardToken(): ?string;

    /**
     * CreditCardPaymentAcceptor：クレジットカード支払方法
     *
     * @return null|int
     */
    public function getCreditCardPaymentMethod(): ?int;

    /**
     * CreditCardPaymentAcceptor：クレジットカード支払回数
     *
     * @return null|int
     */
    public function getCreditCardPayTimes(): ?int;
}