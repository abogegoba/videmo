<?php


namespace App\Business\Interfaces\Interactors\Admin\MessageList;


interface AdminMessageListInitializeInteractor
{
    /**
     * 初期表示
     *
     * @param AdminMessageListInitializeInputPort $inputPort
     * @param AdminMessageListInitializeOutputPort $outputPort
     */
    public function initialize(AdminMessageListInitializeInputPort $inputPort, AdminMessageListInitializeOutputPort $outputPort): void;
}