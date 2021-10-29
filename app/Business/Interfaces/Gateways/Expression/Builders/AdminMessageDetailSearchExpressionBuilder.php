<?php

namespace App\Business\Interfaces\Gateways\Expression\Builders;

/**
 * Interface AdminMessageDetailSearchExpressionBuilder
 *
 * @package App\Business\Interfaces\Gateways\Expression\Builders
 */
interface AdminMessageDetailSearchExpressionBuilder
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