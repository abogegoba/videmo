<?php

namespace App\Business\Interfaces\Interactors\Front\Contact;

/**
 * Interface ContactToConfirmInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\Contact
 */
interface ContactToConfirmInteractor
{
    /**
     * 確認画面へ画面遷移
     *
     * @param ContactToConfirmInputPort $inputPort
     * @param ContactToConfirmOutputPort $outputPort
     */
    public function toConfirm(ContactToConfirmInputPort $inputPort, ContactToConfirmOutputPort $outputPort): void;
}