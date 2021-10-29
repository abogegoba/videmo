<?php

namespace App\Business\Interfaces\Interactors\Admin\CompanyEdit;

/**
 * Interface CompanyEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\CompanyEdit
 */
interface CompanyEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanyEditInitializeInputPort $inputPort
     * @param CompanyEditInitializeOutputPort $outputPort
     */
    public function initialize(CompanyEditInitializeInputPort $inputPort, CompanyEditInitializeOutputPort $outputPort): void;
}