<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate;

/**
 * Interface CompanyRecruitingCreateStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate
 */
interface CompanyRecruitingCreateStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param CompanyRecruitingCreateStoreInputPort $inputPort
     * @param CompanyRecruitingCreateStoreOutputPort $outputPort
     */
    public function create(CompanyRecruitingCreateStoreInputPort $inputPort, CompanyRecruitingCreateStoreOutputPort $outputPort): void;
}