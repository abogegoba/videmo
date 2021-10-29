<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryToNextPageThreeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryToNextPageThreeInteractor
{
    /**
     * 次のページへ３
     *
     * @param MemberEntryToNextPageThreeInputPort $inputPort
     * @param MemberEntryToNextPageThreeOutputPort $outputPort
     */
    public function toNextPageThree(MemberEntryToNextPageThreeInputPort $inputPort, MemberEntryToNextPageThreeOutputPort $outputPort): void;
}