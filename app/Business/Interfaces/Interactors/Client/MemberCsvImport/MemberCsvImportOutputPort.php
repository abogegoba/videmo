<?php

namespace App\Business\Interfaces\Interactors\Client\MemberCsvImport;

use App\Business\Interfaces\Interactors\Backend\CsvImport\CsvImportOutputPort;

/**
 * Interface MemberCsvImportOutputPort
 *
 * @package App\Business\Interfaces\Interactors\Client\MemberCsvImport
 *
 * @property array $infoMessages インフォメッセージ
 * @property array $errorMessages エラーメッセージ
 */
interface MemberCsvImportOutputPort extends CsvImportOutputPort
{
}