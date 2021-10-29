<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate;

/**
 * Interface CompanyRecruitingCreateInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate
 */
interface CompanyRecruitingCreateInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanyRecruitingCreateInitializeInputPort $inputPort
     * @param CompanyRecruitingCreateInitializeOutputPort $outputPort
     */
    public function initialize(CompanyRecruitingCreateInitializeInputPort $inputPort, CompanyRecruitingCreateInitializeOutputPort $outputPort): void;
}