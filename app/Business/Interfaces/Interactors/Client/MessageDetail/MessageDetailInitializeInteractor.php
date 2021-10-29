<?php

namespace App\Business\Interfaces\Interactors\Client\MessageDetail;

/**
 * Interface MessageDetailInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberEntry
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