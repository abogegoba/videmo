<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit;

/**
 * Interface CompanyBasicInformationEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit
 */
interface CompanyBasicInformationEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param CompanyBasicInformationEditStoreInputPort $inputPort
     * @param CompanyBasicInformationEditStoreOutputPort $outputPort
     */
    public function store(CompanyBasicInformationEditStoreInputPort $inputPort, CompanyBasicInformationEditStoreOutputPort $outputPort): void;
}