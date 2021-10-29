<?php

namespace App\Business\Interfaces\Interactors\Client\StudentSearch;

/**
 * Interface StudentSearchInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\StudentSearch
 */
interface StudentSearchInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param StudentSearchInitializeInputPort $inputPort
     * @param StudentSearchInitializeOutputPort $outputPort
     */
    public function initialize(StudentSearchInitializeInputPort $inputPort, StudentSearchInitializeOutputPort $outputPort): void;
}