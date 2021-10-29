<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryConfirmInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryConfirmInitializeInteractor
{
    /**
     * 確認画面を初期表示する
     *
     * @param MemberEntryConfirmInitializeInputPort $inputPort
     * @param MemberEntryConfirmInitializeOutputPort $outputPort
     */
    public function confirmInitialize(MemberEntryConfirmInitializeInputPort $inputPort, MemberEntryConfirmInitializeOutputPort $outputPort): void;
}