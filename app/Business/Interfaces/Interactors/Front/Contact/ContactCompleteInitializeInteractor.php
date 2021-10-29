<?php

namespace App\Business\Interfaces\Interactors\Front\Contact;

/**
 * Interface ContactCompleteInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\Contact
 */
interface ContactCompleteInitializeInteractor
{
    /**
     * 完了画面初期表示
     *
     * @param ContactCompleteInitializeInputPort $inputPort
     * @param ContactCompleteInitializeOutputPort $outputPort
     */
    public function completeInitialize(ContactCompleteInitializeInputPort $inputPort, ContactCompleteInitializeOutputPort $outputPort): void;
}