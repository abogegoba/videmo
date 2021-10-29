<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberCreate;

/**
 * Interface MemberCreateInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberCreate
 */
interface MemberCreateInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param MemberCreateInitializeInputPort $inputPort
     * @param MemberCreateInitializeOutputPort $outputPort
     */
    public function initialize(MemberCreateInitializeInputPort $inputPort, MemberCreateInitializeOutputPort $outputPort): void;
}