<?php

namespace App\Business\Interfaces\Interactors\Client\ClientAuthentication;

/**
 * Interface ClientAuthenticationCheckInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\ClientAuthentication
 */
interface ClientAuthenticationCheckInteractor
{
    /**
     * 認証を確認する
     *
     * @param ClientAuthenticationCheckInputPort $inputPort
     * @param ClientAuthenticationCheckOutputPort $outputPort
     */
    public function check(ClientAuthenticationCheckInputPort $inputPort, ClientAuthenticationCheckOutputPort $outputPort): void;
}