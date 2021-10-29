<?php

namespace App\Business\Interfaces\Interactors\Backend\CsvImport;

/**
 * Interface CsvImportInteractor
 *
 * @package App\Business\Interfaces\Interactors\Backend\CsvImport
 */
interface CsvImportInteractor
{
    /**
     * CSV取込を実行する
     *
     * @param CsvImportInputPort $inputPort
     * @param CsvImportOutputPort $outputPort
     */
    public function execute($inputPort, $outputPort): void;
}