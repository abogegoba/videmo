<?php

namespace ReLab\Commons\Exceptions;

/**
 * Class 変換失敗例外
 *
 * @package ReLab\Commons\Exceptions
 */
class ConvertFailedException extends FatalException
{
    /**
     * 本例外のベースとなる例外キーを取得する
     *
     * @return string
     */
    protected function getBaseKey(): string
    {
        $key = parent::getBaseKey();
        return $key.".convert_failed";
    }
}