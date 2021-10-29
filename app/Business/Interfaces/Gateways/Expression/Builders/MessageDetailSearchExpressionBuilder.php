<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface GeneralExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface MessageDetailSearchExpressionBuilder
{
    /**
     * 相手のユーザーカウントID
     *
     * @param null|int $opponentUserAccountId
     */
    public function setOpponentUserAccountId(?int $opponentUserAccountId): void;

    /**
     * 自分のユーザーカウントID
     *
     * @param null|int $oneselfUserAccountId
     */
    public function setOneselfUserAccountId(?int $oneselfUserAccountId): void;
}