<?php

namespace App\Business\Interfaces\Interactors\Admin\CompanyEdit;

/**
 * Interface CompanyEditUpdateInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\CompanyEdit
 */
interface CompanyEditUpdateInteractor
{
    /**
     * 変更する
     *
     * @param CompanyEditUpdateInputPort $inputPort
     * @param CompanyEditUpdateOutputPort $outputPort
     */
    public function update(CompanyEditUpdateInputPort $inputPort, CompanyEditUpdateOutputPort $outputPort): void;
}