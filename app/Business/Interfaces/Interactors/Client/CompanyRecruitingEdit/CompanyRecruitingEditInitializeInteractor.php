<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit;

/**
 * Interface CompanyRecruitingEditInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\ProfileAddressEdit
 */
interface CompanyRecruitingEditInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanyRecruitingEditInitializeInputPort $inputPort
     * @param CompanyRecruitingEditInitializeOutputPort $outputPort
     */
    public function initialize(CompanyRecruitingEditInitializeInputPort $inputPort, CompanyRecruitingEditInitializeOutputPort $outputPort): void;
}