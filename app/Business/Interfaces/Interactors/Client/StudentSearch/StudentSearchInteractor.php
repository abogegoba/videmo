<?php

namespace App\Business\Interfaces\Interactors\Client\StudentSearch;

/**
 * Interface StudentSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\StudentSearch
 */
interface StudentSearchInteractor
{
    /**
     * 検索する
     *
     * @param StudentSearchInputPort $inputPort
     * @param StudentSearchOutputPort $outputPort
     */
    public function search(StudentSearchInputPort $inputPort, StudentSearchOutputPort $outputPort): void;
}