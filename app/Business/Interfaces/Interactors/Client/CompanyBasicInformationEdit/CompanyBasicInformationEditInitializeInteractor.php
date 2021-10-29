<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit;

/**
 * Interface CompanyBasicInformationEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit
 */
interface CompanyBasicInformationEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanyBasicInformationEditInitializeInputPort $inputPort
     * @param CompanyBasicInformationEditInitializeOutputPort $outputPort
     */
    public function initialize(CompanyBasicInformationEditInitializeInputPort $inputPort, CompanyBasicInformationEditInitializeOutputPort $outputPort): void;
}