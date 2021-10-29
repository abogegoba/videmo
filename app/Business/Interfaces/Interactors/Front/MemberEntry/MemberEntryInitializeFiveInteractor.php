<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryInitializeFiveInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryInitializeFiveInteractor
{
    /**
     * 初期化する５
     *
     * @param MemberEntryInitializeFiveInputPort $inputPort
     * @param MemberEntryInitializeFiveOutputPort $outputPort
     */
    public function initializeFive(MemberEntryInitializeFiveInputPort $inputPort, MemberEntryInitializeFiveOutputPort $outputPort): void;
}