<?php

namespace App\Business\Interfaces\Interactors\Admin\MemberDelete;

/**
 * Interface MemberDeleteInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MemberDelete
 */
interface MemberDeleteInteractor
{
    /**
     * @param MemberDeleteInputPort $inputPort
     * @param MemberDeleteOutputPort $outputPort
     */
    public function destroy(MemberDeleteInputPort $inputPort, MemberDeleteOutputPort $outputPort): void;
}