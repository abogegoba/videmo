<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberEdit;

/**
 * Interface MemberCreateStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberCreate
 */
interface MemberEditUpdateInteractor
{
    /**
     * 変更する
     *
     * @param MemberEditUpdateInputPort $inputPort
     * @param MemberEditUpdateOutputPort $outputPort
     */
    public function update(MemberEditUpdateInputPort $inputPort, MemberEditUpdateOutputPort $outputPort): void;
}