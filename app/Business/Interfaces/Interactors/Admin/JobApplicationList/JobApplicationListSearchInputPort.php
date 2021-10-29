<?php


namespace App\Business\Interfaces\Interactors\Admin\JobApplicationList;

use ReLab\Commons\Interfaces\Pager;

/**
 * Interface JobApplicationListSearchInputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationList
 *
 * @property Pager $pager ページャー
 * @property string companyName 会社名
 * @property string companyNameKana 会社名かな
 * @property int|null $status ステータス
 * @property string|null $area 勤務地域
 */
interface JobApplicationListSearchInputPort
{
}