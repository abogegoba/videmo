<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberList;

use ReLab\Commons\Interfaces\Pager;

/**
 * Interface MemberListSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberList
 *
 * @property Member[]|Data $members 会員（検索結果）
 * @property Pager $pager ページャー
 */
interface MemberListSearchOutputPort
{
}