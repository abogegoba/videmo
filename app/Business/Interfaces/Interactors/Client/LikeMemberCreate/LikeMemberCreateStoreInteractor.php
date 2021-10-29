<?php


namespace App\Business\Interfaces\Interactors\Client\LikeMemberCreate;

/**
 * Interface LikeMemberCreateStoreInteractor
 *
 * @package App\Business\Interfaces\Interactors\Client\LikeMemberCreate
 */

class LikeMemberCreateStoreInteractor
{
    /**
     * 登録変更する
     *
     * @param LikeMemberCreateStoreInputPort $inputPort
     * @param LikeMemberCreateStoreOutputPort $outputPort
     */
    public function create(LikeMemberCreateStoreInputPort $inputPort, LikeMemberCreateStoreOutputPort $outputPort): void;
}
