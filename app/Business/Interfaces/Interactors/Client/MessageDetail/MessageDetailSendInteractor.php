<?php

namespace App\Business\Interfaces\Interactors\Client\MessageDetail;

/**
 * Interface MessageDetailSendInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberEntry
 */
interface MessageDetailSendInteractor
{
    /**
     * メッセージを送信する
     *
     * @param MessageDetailSendInputPort $inputPort
     * @param MessageDetailSendOutputPort $outputPort
     */
    public function sendMessage(MessageDetailSendInputPort $inputPort, MessageDetailSendOutputPort $outputPort): void;
}