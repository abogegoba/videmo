<?php

namespace App\Business\Interfaces\Interactors\Client\LikeMemberCreate;

/**
 * Interface LikeMemberCreateInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\LikeMemberCreate
 */
interface LikeMemberCreateInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param LikeMemberCreateInitializeInputPort $inputPort
     * @param LikeMemberCreateInitializeOutputPort $outputPort
     */
    public function initialize(LikeMemberCreateInitializeInputPort $inputPort, LikeMemberCreateInitializeOutputPort $outputPort): void;
}
