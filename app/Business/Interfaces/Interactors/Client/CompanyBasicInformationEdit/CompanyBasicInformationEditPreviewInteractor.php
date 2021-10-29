<?php

namespace App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit;

/**
 * Interface CompanyBasicInformationEditPreviewInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit
 */
interface CompanyBasicInformationEditPreviewInteractor
{
    /**
     * 登録変更する
     *
     * @param CompanyBasicInformationEditPreviewInputPort $inputPort
     * @param CompanyBasicInformationEditPreviewOutputPort $outputPort
     */
    public function preview(CompanyBasicInformationEditPreviewInputPort $inputPort, CompanyBasicInformationEditPreviewOutputPort $outputPort): void;
}