<?php

namespace ReLab\Commons\Exceptions;

/**
 * Class 業務例外
 *
 * @package ReLab\Commons\Exceptions
 */
class BusinessException extends Exception
{
    /**
     * 本例外のベースとなる例外キーを取得する
     *
     * @return string
     */
    protected function getBaseKey(): string
    {
        return "business";
    }
}