<?php

namespace App\Business\Interfaces\Interactors\Admin\CompanyEdit;

/**
 * Interface CompanyEditUpdateInputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\CompanyEdit
 *
 * @property string $mailAddress メールアドレス
 */
interface CompanyEditUpdateInputPort extends CompanyEditInitializeInputPort, CompanyEditInitializeOutputPort
{
}