<?php

namespace ReLab\Commons\Wrappers;

use Carbon\Carbon;
use ReLab\Commons\Utilities\UUID;

/**
 * Class Transaction
 *
 * トランザクション
 *
 * @package ReLab\Commons\Wrappers
 */
abstract class Transaction
{
    /**
     * インスタンス
     *
     * @var Transaction
     */
    private static $transaction;

    /**
     * トランザクション日時
     *
     * @var Carbon;
     */
    private $dateTime;

    /**
     * トランザクションId
     *
     * @var string;
     */
    private $trancationId;

    /**
     * 実装する
     *
     * @param Transaction $transaction
     */
    public static function implement(Transaction $transaction): void
    {
        self::$transaction = $transaction;
        self::$transaction->dateTime = Carbon::now();
        self::$transaction->trancationId = UUID::generate(UUID::UUID_RANDOM, UUID::FMT_STRING);
    }

    /**
     * インスタンスを取得する
     *
     * @return null|Transaction
     */
    public static function getInstance(): ?Transaction
    {
        return self::$transaction;
    }

    /**
     * トランザクション日時を取得する
     *
     * @return Carbon
     */
    public function getDateTime(): Carbon
    {
        return $this->dateTime->copy();
    }

    /**
     * トランザクションIdを取得する
     *
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->trancationId;
    }

    /**
     * 開始
     */
    abstract public function start(): void;

    /**
     * ロールバック
     */
    abstract public function rollBack(): void;

    /**
     * コミット
     */
    abstract public function commit(): void;
}
