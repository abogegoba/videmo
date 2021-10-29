<?php


namespace App\Business\Interfaces\Interactors\Admin\MessageList;

/**
 * Interface AdminMessageListSearchInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MessageList
 */
interface AdminMessageListSearchInteractor
{
    /**
     * 検索する
     *
     * @param AdminMessageListSearchInputPort $inputPort
     * @param AdminMessageListSearchOutputPort $outputPort
     */
    public function search(AdminMessageListSearchInputPort $inputPort, AdminMessageListSearchOutputPort $outputPort): void;
}