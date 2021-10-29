<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryInitializeThreeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryInitializeThreeInteractor
{
    /**
     * 初期化する３
     *
     * @param MemberEntryInitializeThreeInputPort $inputPort
     * @param MemberEntryInitializeThreeOutputPort $outputPort
     */
    public function initializeThree(MemberEntryInitializeThreeInputPort $inputPort, MemberEntryInitializeThreeOutputPort $outputPort): void;
}