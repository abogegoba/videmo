<?php

namespace App\Business\Interfaces\Interactors\Front\Contact;

/**
 * Interface ContactExecuteInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\Contact
 */
interface ContactExecuteInteractor
{
    /**
     * 問い合わせ実行
     *
     * @param ContactExecuteInputPort $inputPort
     * @param ContactExecuteOutputPort $outputPort
     */
    public function execute(ContactExecuteInputPort $inputPort, ContactExecuteOutputPort $outputPort): void;
}