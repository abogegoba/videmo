<?php

namespace App\Business\Interfaces\Interactors\Admin\AdminAuthentication;

/**
 * Interface AdminAuthenticationLogoutInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\AdminAuthentication
 */
interface AdminAuthenticationLogoutInteractor
{
    /**
     * ログアウトする
     *
     * @param AdminAuthenticationLogoutInputPort $inputPort
     * @param AdminAuthenticationLogoutOutputPort $outputPort
     */
    public function logout(AdminAuthenticationLogoutInputPort $inputPort, AdminAuthenticationLogoutOutputPort $outputPort): void;
}