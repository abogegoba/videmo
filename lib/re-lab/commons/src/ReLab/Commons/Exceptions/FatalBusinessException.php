<?php

namespace ReLab\Commons\Exceptions;

/**
 * Class 共通例外（致命的業務例外）
 *
 * @package ReLab\Commons\Exceptions
 */
class FatalBusinessException extends Exception
{
    /**
     * 本例外のベースとなる例外キーを取得する
     *
     * @return string
     */
    protected function getBaseKey(): string
    {
        return "fatal_business";
    }
}