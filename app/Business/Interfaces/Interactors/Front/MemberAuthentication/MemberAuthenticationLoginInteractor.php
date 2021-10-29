<?php

namespace App\Business\Interfaces\Interactors\Front\MemberAuthentication;

/**
 * Interface MemberAuthenticationLoginInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberAuthentication
 */
interface MemberAuthenticationLoginInteractor
{
    /**
     * ログインする
     *
     * @param MemberAuthenticationLoginInputPort $inputPort
     * @param MemberAuthenticationLoginOutputPort $outputPort
     */
    public function login(MemberAuthenticationLoginInputPort $inputPort, MemberAuthenticationLoginOutputPort $outputPort): void;
}