<?php


namespace App\Business\Interfaces\Interactors\Admin\CompanyCreate;


interface CompanyCreateStoreInteractor
{
    /**
     * 登録する
     *
     * @param CompanyCreateStoreInputPort $inputPort
     * @param CompanyCreateStoreOutputPort $outputPort
     */
    public function store(CompanyCreateStoreInputPort $inputPort, CompanyCreateStoreOutputPort $outputPort): void;
}