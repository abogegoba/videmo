<?php

namespace App\Business\Interfaces\Gateways\Validators;

/**
 * Interface CsvValidator
 *
 * @package App\Business\Interfaces\Gateways\Validators
 */
interface CsvValidator
{
    /**
     * CSV1行分のバリデーションを実行する
     *
     * @param array $values
     * @param int $index
     * @return array
     */
    public function validate(array $values, int $index): array;

    /**
     * カラム数取得
     *
     * @return int
     */
    public function columnCount(): int;

    /**
     * カラム定義と値をマッピングする
     *
     * @param array $values
     * @return array|null
     */
    public function mappingColumnValues(array $values): ?array;

    /**
     * 指定されたメッセージに行文言を追加する
     *
     * @param array $messages
     * @param int $index
     * @return array
     */
    public function messagesWithIndex(array $messages, int $index): array;
}