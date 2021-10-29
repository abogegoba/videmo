<?php

namespace App\Business\Interfaces\Interactors\Admin\AdminAuthentication;

/**
 * Interface AdminAuthenticationCheckInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\AdminAuthentication
 */
interface AdminAuthenticationCheckInteractor
{
    /**
     * 認証を確認する
     *
     * @param AdminAuthenticationCheckInputPort $inputPort
     * @param AdminAuthenticationCheckOutputPort $outputPort
     */
    public function check(AdminAuthenticationCheckInputPort $inputPort, AdminAuthenticationCheckOutputPort $outputPort): void;
}