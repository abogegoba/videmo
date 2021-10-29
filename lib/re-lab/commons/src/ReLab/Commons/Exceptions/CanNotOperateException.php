<?php

namespace ReLab\Commons\Exceptions;

/**
 * Class 操作権限無しエラー
 *
 * @package ReLab\Commons\Exceptions
 */
class CanNotOperateException extends FatalBusinessException
{
    /**
     * CanNotOperateException constructor.
     */
    public function __construct()
    {
        $key = 'can_not_operate';
        parent::__construct($key);
        $this->addKey($key);
    }
}