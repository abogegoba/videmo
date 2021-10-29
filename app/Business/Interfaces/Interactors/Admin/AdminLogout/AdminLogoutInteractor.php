<?php

namespace App\Business\Interfaces\Interactors\Admin\AdminLogout;

/**
 * Interface AdminLogoutInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\AdminLogout
 */
interface AdminLogoutInteractor
{
    /**
     * ログアウトする
     *
     * @param AdminLogoutInputPort $inputPort
     * @param AdminLogoutOutputPort $outputPort
     */
    public function logout(AdminLogoutInputPort $inputPort, AdminLogoutOutputPort $outputPort): void;
}