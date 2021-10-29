<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberList;

/**
 * Interface MemberListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberList
 */
interface MemberListInitializeInteractor
{
    /**
     * @param MemberListInitializeInputPort $inputPort
     * @param MemberListInitializeOutputPort $outputPort
     */
    public function initialize(MemberListInitializeInputPort $inputPort, MemberListInitializeOutputPort $outputPort): void;
}