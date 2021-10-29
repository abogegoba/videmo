<?php

namespace App\Business\Interfaces\Interactors\Front\MessageDetail;

/**
 * Interface MessageDetailInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
 */
interface MessageDetailInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param MessageDetailInitializeInputPort $inputPort
     * @param MessageDetailInitializeOutputPort $outputPort
     */
    public function initialize(MessageDetailInitializeInputPort $inputPort, MessageDetailInitializeOutputPort $outputPort): void;
}