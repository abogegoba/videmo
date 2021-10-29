<?php

namespace App\Business\Interfaces\Interactors\Front\MemberAuthentication;

/**
 * Interface MemberAuthenticationCheckInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberAuthentication
 */
interface MemberAuthenticationCheckInteractor
{
    /**
     * 認証を確認する
     *
     * @param MemberAuthenticationCheckInputPort $inputPort
     * @param MemberAuthenticationCheckOutputPort $outputPort
     */
    public function check(MemberAuthenticationCheckInputPort $inputPort, MemberAuthenticationCheckOutputPort $outputPort): void;
}