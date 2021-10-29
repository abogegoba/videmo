<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberEdit;

/**
 * Interface MemberEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberEdit
 */
interface MemberEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param MemberEditInitializeInputPort $inputPort
     * @param MemberEditInitializeOutputPort $outputPort
     */
    public function initialize(MemberEditInitializeInputPort $inputPort, MemberEditInitializeOutputPort $outputPort): void;
}