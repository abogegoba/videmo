<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryInitializeTwoInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryInitializeTwoInteractor
{
    /**
     * 初期化する２
     *
     * @param MemberEntryInitializeTwoInputPort $inputPort
     * @param MemberEntryInitializeTwoOutputPort $outputPort
     */
    public function initializeTwo(MemberEntryInitializeTwoInputPort $inputPort, MemberEntryInitializeTwoOutputPort $outputPort): void;
}