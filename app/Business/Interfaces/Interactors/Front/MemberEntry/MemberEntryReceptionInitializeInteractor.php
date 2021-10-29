<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryReceptionInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryReceptionInitializeInteractor
{
    /**
     * 受付画面を初期表示する
     *
     * @param MemberEntryReceptionInitializeInputPort $inputPort
     * @param MemberEntryReceptionInitializeOutputPort $outputPort
     */
    public function receptionInitialize(MemberEntryReceptionInitializeInputPort $inputPort, MemberEntryReceptionInitializeOutputPort $outputPort): void;
}