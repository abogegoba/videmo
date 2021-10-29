<?php

namespace App\Business\Interfaces\Interactors\Client\StudentSearch;

use App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;
use ReLab\Commons\Interfaces\Pager;/**
 * Interface StudentSearchInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\StudentSearch
 * @property Pager $pager ページャー
 */
interface StudentSearchInputPort extends UseLoggedInCompanyAccountInputPort
{
}