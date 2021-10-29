<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyRecruitingDelete;

/**
 * Interface CompanyRecruitingEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\ProfileAddressEdit
 */
interface CompanyRecruitingDeleteInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanyRecruitingDeleteInitializeInputPort $inputPort
     * @param CompanyRecruitingDeleteInitializeOutputPort $outputPort
     */
    public function initialize(CompanyRecruitingDeleteInitializeInputPort $inputPort, CompanyRecruitingDeleteInitializeOutputPort $outputPort): void;
}