<?php

namespace App\Business\Interfaces\Interactors\Front\Top;

/**
 * Interface TopSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\Top
 */
interface TopSearchInteractor
{
    /**
     * 検索画面へ条件保持して遷移
     *
     * @param TopSearchInputPort $inputPort
     * @param TopSearchOutputPort $outputPort
     */
    public function search(TopSearchInputPort $inputPort, TopSearchOutputPort $outputPort): void;
}