<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Interactors\Front\MemberLogout\MemberLogoutInputPort;
use App\Business\Interfaces\Interactors\Front\MemberLogout\MemberLogoutInteractor;
use App\Business\Interfaces\Interactors\Front\MemberLogout\MemberLogoutOutputPort;
use App\Utilities\Log;
/**
 * Class MemberLogoutUseCase
 *
 * @package App\Business\UseCases\Front
 */
class MemberLogoutUseCase implements MemberLogoutInteractor
{
    /**
     * ログアウトする
     *
     * @param MemberLogoutInputPort $inputPort
     * @param MemberLogoutOutputPort $outputPort
     */
    public function logout(MemberLogoutInputPort $inputPort, MemberLogoutOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //ログ出力
        Log::infoOut();
    }
}