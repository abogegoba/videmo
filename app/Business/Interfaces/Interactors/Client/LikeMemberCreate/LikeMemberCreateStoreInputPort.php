<?php

namespace App\Business\Interfaces\Interactors\Client\LikeMemberCreate;

use App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;

/**
 * Interface LikeMemberCreateStoreInputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\LikeMemberCreate
 *
 * @property int $companyId 仕事内容
 * @property int $memberId 雇用形態
 * @property int $status ステータス
 */
interface LikeMemberCreateStoreInputPort extends UseLoggedInCompanyAccountInputPort
{
}
