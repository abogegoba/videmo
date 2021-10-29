<?php

namespace App\Business\Interfaces\Interactors\Client\MemberCsvImport;

use App\Business\Interfaces\Interactors\Backend\CsvImport\CsvImportInputPort;
use Carbon\Carbon;

/**
 * Interface MemberCsvImportInputPort
 *
 * @package App\Business\Interfaces\Interactors\client\MemberCsvImport
 *
 * @property string $csvFilePath CSVファイルパス
 * @property Carbon $execDatetime 実行日時
 */
interface MemberCsvImportInputPort extends CsvImportInputPort
{
}