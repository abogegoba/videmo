<?php

namespace App\Business\Interfaces\Interactors\Front\Top;

/**
 * Interface TopInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\Top
 */
interface TopInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param TopInitializeInputPort $inputPort
     * @param TopInitializeOutputPort $outputPort
     */
    public function initialize(TopInitializeInputPort $inputPort, TopInitializeOutputPort $outputPort): void;
}