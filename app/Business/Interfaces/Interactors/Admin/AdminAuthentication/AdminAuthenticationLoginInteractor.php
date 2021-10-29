<?php

namespace App\Business\Interfaces\Interactors\Admin\AdminAuthentication;

/**
 * Interface AdminAuthenticationLoginInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\AdminAuthentication
 */
interface AdminAuthenticationLoginInteractor
{
    /**
     * ログインする
     *
     * @param AdminAuthenticationLoginInputPort $inputPort
     * @param AdminAuthenticationLoginOutputPort $outputPort
     */
    public function login(AdminAuthenticationLoginInputPort $inputPort, AdminAuthenticationLoginOutputPort $outputPort): void;
}