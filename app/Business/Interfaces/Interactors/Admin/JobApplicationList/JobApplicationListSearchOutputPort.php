<?php


namespace App\Business\Interfaces\Interactors\Admin\JobApplicationList;

use App\Domain\Entities\JobApplication;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\Data;

/**
 * Interface JobApplicationListSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationList
 *
 * @property JobApplication[]|Data $jobApplications 求人リスト
 * @property Pager $pager ページャー
 */
interface JobApplicationListSearchOutputPort
{
}