<?php

namespace App\Business\Interfaces\Interactors\Client\ClientLogout;

/**
 * Interface ClientLogoutInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\ClientLogout
 */
interface ClientLogoutInteractor
{
    /**
     * ログアウトする
     *
     * @param ClientLogoutInputPort $inputPort
     * @param ClientLogoutOutputPort $outputPort
     */
    public function logout(ClientLogoutInputPort $inputPort, ClientLogoutOutputPort $outputPort): void;
}