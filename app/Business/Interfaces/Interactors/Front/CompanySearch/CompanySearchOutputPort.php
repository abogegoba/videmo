<?php

namespace App\Business\Interfaces\Interactors\Front\CompanySearch;

use App\Domain\Entities\Company;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\Data;

/**
 * Interface StudentSearchOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Front\CompanySearch
 * @property Pager $pager ページャー
 * @property Company[]|Data $companies 商品料金
 */
interface CompanySearchOutputPort
{
}