<?php

namespace ReLab\Commons\Exceptions;

/**
 * Class 致命的例外
 *
 * @package ReLab\Commons\Exceptions
 */
class FatalException extends Exception
{
    /**
     * 本例外のベースとなる例外キーを取得する
     *
     * @return string
     */
    protected function getBaseKey(): string
    {
        return "fatal";
    }
}