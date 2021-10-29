<?php

namespace App\Business\Interfaces\Interactors\Front\MemberAuthentication;

/**
 * Interface MemberAuthenticationInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberAuthentication
 */
interface MemberAuthenticationInitializeInteractor
{
    /**
     * 初期化する
     */
    public function initialize(): void;
}