<?php


namespace App\Business\Interfaces\Interactors\Admin\MessageList;


use App\Domain\Entities\Message;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\Data;

/**
 * Interface AdminMessageListSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\MessageList
 *
 * @property Message[]|Data $messages メッセージリスト
 * @property Pager $pager ページャー
 */
interface AdminMessageListSearchOutputPort
{
}