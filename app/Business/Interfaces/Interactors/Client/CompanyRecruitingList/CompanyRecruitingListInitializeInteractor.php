<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyRecruitingList;

/**
 * Interface CompanyRecruitingListInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\CompanyRecruitingList
 */
interface CompanyRecruitingListInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param CompanyRecruitingListInitializeInputPort $inputPort
     * @param CompanyRecruitingListInitializeOutputPort $outputPort
     */
    public function initialize(CompanyRecruitingListInitializeInputPort $inputPort, CompanyRecruitingListInitializeOutputPort $outputPort): void;
}