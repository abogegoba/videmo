<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryInitializeOneInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryInitializeOneInteractor
{
    /**
     * 初期化する１
     *
     * @param MemberEntryInitializeOneInputPort $inputPort
     * @param MemberEntryInitializeOneOutputPort $outputPort
     */
    public function initializeOne(MemberEntryInitializeOneInputPort $inputPort, MemberEntryInitializeOneOutputPort $outputPort): void;
}