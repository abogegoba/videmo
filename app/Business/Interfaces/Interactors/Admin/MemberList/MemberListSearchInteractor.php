<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberList;

/**
 * Interface MemberListSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberList
 */
interface MemberListSearchInteractor
{
    /**
     * 検索
     *
     * @param MemberListSearchInputPort $inputPort
     * @param MemberListSearchOutputPort $outputPort
     */
    public function search(MemberListSearchInputPort $inputPort, MemberListSearchOutputPort $outputPort): void;
}