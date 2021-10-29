<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface GeneralExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface MessageSearchExpressionBuilder
{
    /**
     * メッセージをやり取りするユーザーカウントID
     *
     * @param null|int exchangeUserAccountId
     */
    public function setExchangeUserAccountId(?int $exchangeUserAccountId): void;
}