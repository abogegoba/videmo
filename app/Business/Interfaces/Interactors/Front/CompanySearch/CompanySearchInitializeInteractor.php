<?php

namespace App\Business\Interfaces\Interactors\Front\CompanySearch;

/**
 * Interface StudentSearchInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\CompanySearch
 */
interface CompanySearchInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanySearchInitializeInputPort $inputPort
     * @param CompanySearchInitializeOutputPort $outputPort
     */
    public function initialize(CompanySearchInitializeInputPort $inputPort, CompanySearchInitializeOutputPort $outputPort): void;
}