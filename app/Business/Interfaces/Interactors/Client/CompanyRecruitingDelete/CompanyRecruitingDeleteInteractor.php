<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyRecruitingDelete;

/**
 * Interface CompanyRecruitingDeleteInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\CompanyRecruitingDelete
 */
interface CompanyRecruitingDeleteInteractor
{
    /**
     * 登録変更する
     *
     * @param CompanyRecruitingDeleteInputPort $inputPort
     * @param CompanyRecruitingDeleteOutputPort $outputPort
     */
    public function delete(CompanyRecruitingDeleteInputPort $inputPort, CompanyRecruitingDeleteOutputPort $outputPort): void;
}