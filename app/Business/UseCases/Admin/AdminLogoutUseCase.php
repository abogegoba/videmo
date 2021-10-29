<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Interactors\Admin\AdminLogout\AdminLogoutInputPort;
use App\Business\Interfaces\Interactors\Admin\AdminLogout\AdminLogoutInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminLogout\AdminLogoutOutputPort;
use App\Utilities\Log;

/**
 * Class AdminLogoutUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class AdminLogoutUseCase implements AdminLogoutInteractor
{
    /**
     * ログアウトする
     *
     * @param AdminLogoutInputPort $inputPort
     * @param AdminLogoutOutputPort $outputPort
     */
    public function logout(AdminLogoutInputPort $inputPort, AdminLogoutOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //ログ出力
        Log::infoOut();
    }
}