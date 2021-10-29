<?php


namespace App\Business\Interfaces\Interactors\Admin\CompanyCreate;

/**
 * Interface CompanyCreateInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\CompanyCreate
 */
interface CompanyCreateInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanyCreateInitializeInputPort $inputPort
     * @param CompanyCreateInitializeOutputPort $outputPort
     */
    public function initialize(CompanyCreateInitializeInputPort $inputPort, CompanyCreateInitializeOutputPort $outputPort): void;
}