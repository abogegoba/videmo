<?php

namespace App\Business\Interfaces\Interactors\Front\CompanyDetail;

/**
 * Interface StudentDetailInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\CompanyDetail
 */
interface CompanyDetailInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanyDetailInitializeInputPort $inputPort
     * @param CompanyDetailInitializeOutputPort $outputPort
     */
    public function initialize(CompanyDetailInitializeInputPort $inputPort, CompanyDetailInitializeOutputPort $outputPort): void;
}