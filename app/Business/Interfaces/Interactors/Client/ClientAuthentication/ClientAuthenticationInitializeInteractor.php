<?php

namespace App\Business\Interfaces\Interactors\Client\ClientAuthentication;

/**
 * Interface ClientAuthenticationInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\ClientAuthentication
 */
interface ClientAuthenticationInitializeInteractor
{
    /**
     * 初期化する
     */
    public function initialize(): void;
}