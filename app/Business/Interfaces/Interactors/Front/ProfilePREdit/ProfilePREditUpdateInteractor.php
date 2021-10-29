<?php

namespace App\Business\Interfaces\Interactors\Front\ProfilePREdit;

/**
 * Interface ProfilePREditUpdateInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfilePREdit
 */
interface ProfilePREditUpdateInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfilePREditUpdateInputPort $inputPort
     * @param ProfilePREditUpdateOutputPort $outputPort
     */
    public function update(ProfilePREditUpdateInputPort $inputPort, ProfilePREditUpdateOutputPort $outputPort): void;
}