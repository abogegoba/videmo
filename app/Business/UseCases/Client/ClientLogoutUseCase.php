<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Interactors\Client\ClientLogout\ClientLogoutInputPort;
use App\Business\Interfaces\Interactors\Client\ClientLogout\ClientLogoutInteractor;
use App\Business\Interfaces\Interactors\Client\ClientLogout\ClientLogoutOutputPort;
use App\Utilities\Log;
/**
 * Class ClientLogoutUseCase
 *
 * @package App\Business\UseCases\Client
 */
class ClientLogoutUseCase implements ClientLogoutInteractor
{
    /**
     * ログアウトする
     *
     * @param ClientLogoutInputPort $inputPort
     * @param ClientLogoutOutputPort $outputPort
     */
    public function logout(ClientLogoutInputPort $inputPort, ClientLogoutOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //ログ出力
        Log::infoOut();
    }
}