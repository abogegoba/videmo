<?php

namespace App\Business\Interfaces\Interactors\Client\ClientAuthentication;

/**
 * Interface ClientAuthenticationLoginInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\ClientAuthentication
 */
interface ClientAuthenticationLoginInteractor
{
    /**
     * ログインする
     *
     * @param ClientAuthenticationLoginInputPort $inputPort
     * @param ClientAuthenticationLoginOutputPort $outputPort
     */
    public function login(ClientAuthenticationLoginInputPort $inputPort, ClientAuthenticationLoginOutputPort $outputPort): void;
}