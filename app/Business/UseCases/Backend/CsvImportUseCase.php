<?php

namespace App\Business\UseCases\Backend;

use App\Business\Interfaces\Interactors\Backend\CsvImport\CsvImportInputPort;
use App\Business\Interfaces\Interactors\Backend\CsvImport\CsvImportInteractor;
use App\Business\Interfaces\Interactors\Backend\CsvImport\CsvImportOutputPort;
use App\Utilities\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ReLab\Commons\Exceptions\BusinessException;
use SplFileObject;

/**
 * Class CsvImportUseCase
 *
 * @package App\Business\UseCases\Backend
 */
abstract class CsvImportUseCase implements CsvImportInteractor
{
    /**
     * CSV取込を実行する
     *
     * @param CsvImportInputPort $inputPort
     * @param CsvImportOutputPort $outputPort
     * @throws BusinessException
     */
    public function execute($inputPort, $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // CSV取込の前処理を実行する
        $this->before($inputPort, $outputPort);

        $csv = new SplFileObject($inputPort->csvFilePath);
        $csv->setFlags(SplFileObject::READ_CSV);

        // CSVヘッダー行除去有無を判定しヘッダー行を除外する
        $headers = [];
        if ($this->excludeHeaderRow()) {
            if (!$csv->eof()) {
                $headers = $csv->fgetcsv();
                $csv->next();
            }
        }

        // CSV行のバリデーションを実行する
        $index = 1;
        $validValues = [];
        $invalidValues = [];
        $allErrors = [];
        $checkedValues = [];
        while (!$csv->eof()) {
            $values = $csv->fgetcsv();
            if (0 < count(array_filter($values))) {
                $errors = $this->validate($values, $index, $headers, $checkedValues, $inputPort, $outputPort);
                if (empty($errors)) {
                    // エラーが存在しない場合は成功した有効な値として配列に追加する
                    $validValues[] = $values;
                } else {
                    // エラーが存在した場合はエラーメッセージと無効な値として配列に追加する
                    $allErrors = array_merge($allErrors, $errors);
                    $invalidValues[] = $values;
                }
                $checkedValues[] = $values;
            }
            $csv->next();
            $index++;
        }
        $csv = null;

        // ファイル内にデータが無ければエラーへ
        if (empty($checkedValues)) {
            throw new BusinessException("csv.data_not_found");
        }

        // エラーが全て存在しない場合、登録or更新を実行する
        if (empty($allErrors)) {
            $this->saveOrUpdate($validValues, $headers, $inputPort, $outputPort);
        }

        // CSV取込の後処理を実行する
        $this->after($validValues, $invalidValues, $headers, $allErrors, $inputPort, $outputPort);

        //ログ出力
        Log::infoOut();
    }

    /**
     * マルチプルインサートを実行する
     *
     * @param string $insertTableName
     * @param array $bulkInsertParam
     */
    protected function executeMultipleInsert(string $insertTableName, array $bulkInsertParam): void
    {
        $insertValueString = "";
        foreach ($bulkInsertParam as $insertParam) {
            $arrayKeys = array_keys($insertParam);
            $insertItemString = "(";
            $insertValueString = $insertValueString . "(";
            foreach ($arrayKeys as $key) {
                $insertItemString = $insertItemString . $key . ', ';

                if (is_string($insertParam[$key]) || $insertParam[$key] instanceof Carbon) {
                    $insertValueString = $insertValueString . '\'' . $insertParam[$key] . '\', ';
                } else if(is_null($insertParam[$key])) {
                    $insertValueString = $insertValueString . 'null, ';
                } else {
                    $insertValueString = $insertValueString . $insertParam[$key] . ', ';
                }
            }
            $insertItemString = substr($insertItemString, 0, -2) . ')';
            $insertValueString = substr($insertValueString, 0, -2) . '), ';
        }
        $insertValueString = substr($insertValueString, 0, -2);

        DB::statement('INSERT INTO ' . $insertTableName . ' '
            . $insertItemString . ' VALUES ' . $insertValueString);
    }

    /**
     * CSVヘッダー行除去有無
     *
     * @return bool
     */
    protected function excludeHeaderRow(): bool
    {
        return true;
    }

    /**
     * CSV取込の前処理を実行する
     *
     * @param CsvImportInputPort $inputPort
     * @param CsvImportOutputPort $outputPort
     */
    protected function before($inputPort, $outputPort): void
    {
    }

    /**
     * CSV取込の後処理を実行する
     *
     * @param array $validValues
     * @param array $invalidValues
     * @param array $headers
     * @param array $errors
     * @param $inputPort
     * @param $outputPort
     */
    protected function after(array $validValues, array $invalidValues, array $headers, array $errors, $inputPort, $outputPort): void
    {
    }

    /**
     * CSV行のバリデーションを実行する
     *
     * @param array $values
     * @param int $index
     * @param array $headers
     * @param array $checkedValues
     * @param $inputPort
     * @param $outputPort
     * @return array
     */
    abstract protected function validate(array $values, int $index, array $headers, array $checkedValues, $inputPort, $outputPort): array;

    /**
     * 登録or更新する
     *
     * @param array $validValues
     * @param array $headers
     * @param $inputPort
     * @param $outputPort
     */
    abstract protected function saveOrUpdate(array $validValues, array $headers, $inputPort, $outputPort): void;
}