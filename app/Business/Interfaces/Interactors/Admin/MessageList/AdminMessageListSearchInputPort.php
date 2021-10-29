<?php


namespace App\Business\Interfaces\Interactors\Admin\MessageList;


use ReLab\Commons\Interfaces\Pager;

/**
 * Interface AdminMessageListSearchInputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\MessageList
 *
 * @property Pager $pager ページャー
 * @property string companyName 会社名
 * @property string name 会員名
 * @property array|null ステータス
 */
interface AdminMessageListSearchInputPort
{
}