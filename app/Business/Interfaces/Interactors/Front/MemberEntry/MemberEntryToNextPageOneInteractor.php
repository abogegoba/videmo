<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryToNextPageOneInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryToNextPageOneInteractor
{
    /**
     * 次のページへ１
     *
     * @param MemberEntryToNextPageOneInputPort $inputPort
     * @param MemberEntryToNextPageOneOutputPort $outputPort
     */
    public function toNextPageOne(MemberEntryToNextPageOneInputPort $inputPort, MemberEntryToNextPageOneOutputPort $outputPort): void;
}