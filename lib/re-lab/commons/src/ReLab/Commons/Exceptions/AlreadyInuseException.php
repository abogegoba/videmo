<?php

namespace ReLab\Commons\Exceptions;

/**
 * Class 使用済み例外（エンティティ側で使用します。UseCaseでcatchして使用してください。）
 *
 * @package ReLab\Commons\Exceptions
 */
class AlreadyInuseException extends Exception
{
    /**
     * 本例外のベースとなる例外キーを取得する
     *
     * @return string
     */
    protected function getBaseKey(): string
    {
        return "already_inuse";
    }
}