<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate;

use App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;

/**
 * Interface CompanyRecruitingCreateStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate
 *
 * @property string $title 求人タイトル
 * @property int $jobType 募集職種
 * @property string $recruitmentJobTypeDescription 募集職種説明
 * @property string $jobDescription 仕事内容
 * @property int $employmentType 雇用形態
 * @property string $statue 求める人物像
 * @property string $screeningMethod 選考方法
 * @property string $compensation 給与
 * @property string $bonus 賞与／昇給／手当
 * @property string $area1 勤務地（１）
 * @property string $area2 勤務地（２）
 * @property string $area3 勤務地（３）
 * @property string $dutyHours 勤務時間
 * @property string $compensationPackage 待遇／福利厚生
 * @property string $nonWorkDay 休日・休暇
 * @property string $recruitmentNumber 採用予定人数
 * @property int $status ステータス
 */
interface CompanyRecruitingCreateStoreInputPort extends UseLoggedInCompanyAccountInputPort
{
}