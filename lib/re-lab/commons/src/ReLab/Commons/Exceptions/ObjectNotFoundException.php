<?php

namespace ReLab\Commons\Exceptions;

/**
 * Class オブジェクト非存在例外
 *
 * @package ReLab\Commons\Exceptions
 */
class ObjectNotFoundException extends BusinessException
{
    /**
     * 本例外のベースとなる例外キーを取得する
     *
     * @return string
     */
    protected function getBaseKey(): string
    {
        $key = parent::getBaseKey();
        return $key.".object_not_found";
    }
}