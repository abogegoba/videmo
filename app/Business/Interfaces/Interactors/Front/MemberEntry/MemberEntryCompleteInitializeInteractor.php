<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryCompleteInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryCompleteInitializeInteractor
{
    /**
     * 完了画面を初期表示する
     *
     * @param MemberEntryCompleteInitializeInputPort $inputPort
     * @param MemberEntryCompleteInitializeOutputPort $outputPort
     */
    public function completeInitialize(MemberEntryCompleteInitializeInputPort $inputPort, MemberEntryCompleteInitializeOutputPort $outputPort): void;
}