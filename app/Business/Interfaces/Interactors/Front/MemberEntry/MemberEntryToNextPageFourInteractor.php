<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryToNextPageFourInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryToNextPageFourInteractor
{
    /**
     * 次のページへ４
     *
     * @param MemberEntryToNextPageFourInputPort $inputPort
     * @param MemberEntryToNextPageFourOutputPort $outputPort
     */
    public function toNextPageFour(MemberEntryToNextPageFourInputPort $inputPort, MemberEntryToNextPageFourOutputPort $outputPort): void;
}