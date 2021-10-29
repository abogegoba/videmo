<?php

namespace App\Business\Interfaces\Interactors\Backend\JobStore;

/**
 * Interface JobStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Backend\JobStore
 */
interface JobStoreInteractor
{
    /**
     * ジョブを登録する
     *
     * @param JobStoreInputPort $inputPort
     * @param JobStoreOutputPort $outputPort
     */
    public function store($inputPort, $outputPort): void;
}