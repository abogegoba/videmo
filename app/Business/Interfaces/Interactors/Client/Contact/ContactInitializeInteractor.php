<?php

namespace App\Business\Interfaces\Interactors\Client\Contact;

/**
 * Interface ContactInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\Contact
 */
interface ContactInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ContactInitializeInputPort $inputPort
     * @param ContactInitializeOutputPort $outputPort
     */
    public function initialize(ContactInitializeInputPort $inputPort, ContactInitializeOutputPort $outputPort): void;
}