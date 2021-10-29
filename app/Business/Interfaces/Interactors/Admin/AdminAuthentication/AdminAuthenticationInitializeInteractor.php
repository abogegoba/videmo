<?php

namespace App\Business\Interfaces\Interactors\Admin\AdminAuthentication;

/**
 * Interface AdminAuthenticationInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\AdminAuthentication
 */
interface AdminAuthenticationInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param AdminAuthenticationInitializeInputPort $inputPort
     * @param AdminAuthenticationInitializeOutputPort $outputPort
     */
    public function initialize(AdminAuthenticationInitializeInputPort $inputPort, AdminAuthenticationInitializeOutputPort $outputPort): void;
}