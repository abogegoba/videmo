<?php

namespace ReLab\Payments\Interfaces\Acceptors;

/**
 * Interface PaymentAcceptor
 *
 * 決済受付
 *
 * @package ReLab\Payments\Interfaces\Acceptors
 */
interface PaymentAcceptor
{
    /**
     * PaymentAcceptor：支払い商品・サービス名取得
     *
     * @return null|string
     */
    public function getPaymentOrderName(): ?string;

    /**
     * PaymentAcceptor：注文ID取得
     *
     * @return null|string
     */
    public function getPaymentOrderId(): ?string;

    /**
     * PaymentAcceptor：利用金額取得
     *
     * @return float|null
     */
    public function getPaymentTotalAmount(): ?float;

    /**
     * PaymentAcceptor：税送料取得
     *
     * @return int|null
     */
    public function getPaymentTaxAmount(): ?int;

    /**
     * PaymentAcceptor：商品名
     *
     * @return array|null
     */
    public function getPaymentItemNames(): ?array;

    /**
     * PaymentAcceptor：商品単価
     *
     * @return array|null
     */
    public function getPaymentItemAmounts(): ?array;

    /**
     * PaymentAcceptor：商品小径
     *
     * @return array|null
     */
    public function getPaymentItemSubtotalAmounts(): ?array;

    /**
     * PaymentAcceptor：商品数量
     *
     * @return array|null
     */
    public function getPaymentItemQuantities(): ?array;
}