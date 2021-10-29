<?php

namespace App\Business\Interfaces\Interactors\Admin\CompanyList;

/**
 * Interface CompanyListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\CompanyList
 */
interface CompanyListInitializeInteractor
{
    /**
     * @param CompanyListInitializeInputPort $inputPort
     * @param CompanyListInitializeOutputPort $outputPort
     */
    public function initialize(CompanyListInitializeInputPort $inputPort, CompanyListInitializeOutputPort $outputPort): void;
}