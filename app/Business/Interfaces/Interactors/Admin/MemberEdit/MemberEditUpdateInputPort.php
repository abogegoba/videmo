<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberEdit;

use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateStoreInputPort;

/**
 * Interface MemberEditUpdateInputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberEdit
 *
 * @property int $memberId 会員ID
 */
interface MemberEditUpdateInputPort extends MemberCreateStoreInputPort
{
}