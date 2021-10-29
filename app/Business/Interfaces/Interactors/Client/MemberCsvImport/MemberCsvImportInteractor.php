<?php

namespace App\Business\Interfaces\Interactors\Client\MemberCsvImport;

use App\Business\Interfaces\Interactors\Backend\CsvImport\CsvImportInteractor;

/**
 * Interface MemberCsvImportInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberCsvImport
 */
interface MemberCsvImportInteractor
{
    public function execute($inputPort, $outputPort);
}