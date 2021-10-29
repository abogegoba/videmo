<?php

namespace App\Business\Interfaces\Interactors\Admin\JobApplicationEdit;

use App\Domain\Entities\Company;
use App\Domain\Entities\JobApplication;

/**
 * Interface JobApplicationEditInitializeOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationEdit
 *
 * @property JobApplication $jobApplication 求人
 * @property Company $company 企業
 * @property int $jobType 募集職種
 * @property int $area1 勤務地（1）
 * @property int $area2 勤務地（2）
 * @property int $area3 勤務地（3）
 * @property int $area4 勤務地（4）
 * @property int $area5 勤務地（5）
 * @property int $area6 勤務地（6）
 * @property int $area7 勤務地（7）
 * @property int $area8 勤務地（8）
 * @property int $area9 勤務地（9）
 * @property int $area10 勤務地（10）
 * @property array $companyList 企業リスト
 * @property array $firstRowInJobTypeList 募集職種リストの最初の行
 * @property array $secondRowInJobTypeList 募集職種リストの2行目
 * @property array $thirdRowInJobTypeList 募集職種リストの3行目
 * @property array $employmentTypeList 雇用形態リスト
 * @property array $prefectureList 都道府県リスト
 * @property array $statusDisplayList ステータスリスト
 */
interface JobApplicationEditInitializeOutputPort
{
}
