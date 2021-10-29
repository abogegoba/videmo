<?php

namespace ReLab\Commons\Interfaces;

/**
 * Interface Pager
 *
 * @package ReLab\Commons\Interfaces
 *
 * @property int $index 現在のページ
 * @property int $limit 1ページ最大件数
 * @property int $allCount 全件数
 * @property bool $notDistinct カウント重複あり
 */
interface Pager
{
}