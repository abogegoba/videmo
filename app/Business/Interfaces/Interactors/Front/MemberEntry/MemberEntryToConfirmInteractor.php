<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryToConfirmInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryToConfirmInteractor
{
    /**
     * 確認画面へ
     *
     * @param MemberEntryToConfirmInputPort $inputPort
     * @param MemberEntryToConfirmOutputPort $outputPort
     */
    public function toConfirm(MemberEntryToConfirmInputPort $inputPort, MemberEntryToConfirmOutputPort $outputPort): void;
}