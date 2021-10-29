<?php

namespace App\Business\Interfaces\Interactors\Front\CompanySearch;

/**
 * Interface StudentSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\CompanySearch
 */
interface CompanySearchInteractor
{
    /**
     * 検索する
     *
     * @param CompanySearchInputPort $inputPort
     * @param CompanySearchOutputPort $outputPort
     */
    public function search(CompanySearchInputPort $inputPort, CompanySearchOutputPort $outputPort): void;
}