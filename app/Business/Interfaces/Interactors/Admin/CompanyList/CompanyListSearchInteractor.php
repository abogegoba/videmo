<?php

namespace App\Business\Interfaces\Interactors\Admin\CompanyList;

/**
 * Interface CompanyListSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\CompanyList
 */
interface CompanyListSearchInteractor
{
    /**
     * 検索
     *
     * @param CompanyListSearchInputPort $inputPort
     * @param CompanyListSearchOutputPort $outputPort
     */
    public function search(CompanyListSearchInputPort $inputPort, CompanyListSearchOutputPort $outputPort): void;
}