<?php

namespace App\Business\Interfaces\Interactors\Front\MessageDetail;

/**
 * Interface MessageDetailSendInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\MemberEntry
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