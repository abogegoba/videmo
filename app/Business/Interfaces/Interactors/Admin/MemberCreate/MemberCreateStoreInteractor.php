<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberCreate;

/**
 * Interface MemberCreateStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberCreate
 */
interface MemberCreateStoreInteractor
{
    /**
     * 登録する
     *
     * @param MemberCreateStoreInputPort $inputPort
     * @param MemberCreateStoreOutputPort $outputPort
     */
    public function store(MemberCreateStoreInputPort $inputPort, MemberCreateStoreOutputPort $outputPort): void;
}