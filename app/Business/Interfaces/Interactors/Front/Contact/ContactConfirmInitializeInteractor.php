<?php

namespace App\Business\Interfaces\Interactors\Front\Contact;

/**
 * Interface ContactConfirmInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\Contact
 */
interface ContactConfirmInitializeInteractor
{
    /**
     * 確認画面初期表示
     *
     * @param ContactConfirmInitializeInputPort $inputPort
     * @param ContactConfirmInitializeOutputPort $outputPort
     */
    public function confirmInitialize(ContactConfirmInitializeInputPort $inputPort, ContactConfirmInitializeOutputPort $outputPort): void;
}