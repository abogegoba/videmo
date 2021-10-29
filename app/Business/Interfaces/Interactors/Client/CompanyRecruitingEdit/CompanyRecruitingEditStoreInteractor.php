<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit;

/**
 * Interface CompanyRecruitingEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\ProfileAddressEdit
 */
interface CompanyRecruitingEditStoreInteractor
{
    /**
     * 削除する
     *
     * @param CompanyRecruitingEditStoreInputPort $inputPort
     * @param CompanyRecruitingEditStoreOutputPort $outputPort
     */
    public function edit(CompanyRecruitingEditStoreInputPort $inputPort, CompanyRecruitingEditStoreOutputPort $outputPort): void;
}