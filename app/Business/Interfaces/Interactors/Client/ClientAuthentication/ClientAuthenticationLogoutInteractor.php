<?php

namespace App\Business\Interfaces\Interactors\Client\ClientAuthentication;

/**
 * Interface ClientAuthenticationLogoutInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\ClientAuthentication
 */
interface ClientAuthenticationLogoutInteractor
{
    /**
     * ログアウトする
     */
    public function logout(): void;
}