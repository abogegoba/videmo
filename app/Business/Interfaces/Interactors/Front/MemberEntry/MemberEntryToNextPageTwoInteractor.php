<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryToNextPageTwoInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryToNextPageTwoInteractor
{
    /**
     * 次のページへ３
     *
     * @param MemberEntryToNextPageTwoInputPort $inputPort
     * @param MemberEntryToNextPageTwoOutputPort $outputPort
     */
    public function toNextPageTwo(MemberEntryToNextPageTwoInputPort $inputPort, MemberEntryToNextPageTwoOutputPort $outputPort): void;
}