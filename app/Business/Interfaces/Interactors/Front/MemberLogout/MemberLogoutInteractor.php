<?php

namespace App\Business\Interfaces\Interactors\Front\MemberLogout;

/**
 * Interface MemberLogoutInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberLogout
 */
interface MemberLogoutInteractor
{
    /**
     * ログアウトする
     *
     * @param MemberLogoutInputPort $inputPort
     * @param MemberLogoutOutputPort $outputPort
     */
    public function logout(MemberLogoutInputPort $inputPort, MemberLogoutOutputPort $outputPort): void;
}