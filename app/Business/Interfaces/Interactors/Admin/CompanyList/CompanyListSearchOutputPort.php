<?php

namespace App\Business\Interfaces\Interactors\Admin\CompanyList;

use ReLab\Commons\Interfaces\Pager;

/**
 * Interface CompanyListSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\CompanyList
 *
 * @property Company[]|Data $companies 企業（検索結果）
 * @property array $companyList 企業（検索結果）
 * @property Pager $pager ページャー
 */
interface CompanyListSearchOutputPort
{
}