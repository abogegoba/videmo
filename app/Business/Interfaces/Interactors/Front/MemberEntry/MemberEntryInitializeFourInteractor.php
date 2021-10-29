<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryInitializeFourInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryInitializeFourInteractor
{
    /**
     * 初期化する４
     *
     * @param MemberEntryInitializeFourInputPort $inputPort
     * @param MemberEntryInitializeFourOutputPort $outputPort
     */
    public function initializeFour(MemberEntryInitializeFourInputPort $inputPort, MemberEntryInitializeFourOutputPort $outputPort): void;
}