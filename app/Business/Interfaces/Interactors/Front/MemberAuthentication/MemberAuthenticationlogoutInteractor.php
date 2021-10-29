<?php

namespace App\Business\Interfaces\Interactors\Front\MemberAuthentication;

/**
 * Interface MemberAuthenticationLogoutInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberAuthentication
 */
interface MemberAuthenticationLogoutInteractor
{
    /**
     * ログアウトする
     */
    public function logout(): void;
}