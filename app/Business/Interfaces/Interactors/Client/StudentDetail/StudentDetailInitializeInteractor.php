<?php

namespace App\Business\Interfaces\Interactors\Client\StudentDetail;

/**
 * Interface StudentDetailInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\StudentDetail
 */
interface StudentDetailInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param StudentDetailInitializeInputPort $inputPort
     * @param StudentDetailInitializeOutputPort $outputPort
     */
    public function initialize(StudentDetailInitializeInputPort $inputPort, StudentDetailInitializeOutputPort $outputPort): void;
}