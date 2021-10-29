<?php

namespace App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit;

/**
 * Interface ProfileDesiredEditStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit
 */
interface ProfileDesiredEditStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param ProfileDesiredEditStoreInputPort $inputPort
     * @param ProfileDesiredEditStoreOutputPort $outputPort
     */
    public function store(ProfileDesiredEditStoreInputPort $inputPort, ProfileDesiredEditStoreOutputPort $outputPort): void;
}