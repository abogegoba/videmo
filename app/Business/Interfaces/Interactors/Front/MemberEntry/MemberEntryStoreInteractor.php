<?php

namespace App\Business\Interfaces\Interactors\Front\MemberEntry;

/**
 * Interface MemberEntryStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MemberEntryStoreInteractor
{
    /**
     * 登録する
     *
     * @param MemberEntryStoreInputPort $inputPort
     * @param MemberEntryStoreOutputPort $outputPort
     */
    public function store(MemberEntryStoreInputPort $inputPort, MemberEntryStoreOutputPort $outputPort): void;
}