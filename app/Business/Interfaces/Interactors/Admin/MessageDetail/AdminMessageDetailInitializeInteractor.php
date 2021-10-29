<?php


namespace App\Business\Interfaces\Interactors\Admin\MessageDetail;


interface AdminMessageDetailInitializeInteractor
{
    /**
     * 初期表示
     *
     * @param AdminMessageDetailInitializeInputPort $inputPort
     * @param AdminMessageDetailInitializeOutputPort $outputPort
     */
    public function initialize(AdminMessageDetailInitializeInputPort $inputPort, AdminMessageDetailInitializeOutputPort $outputPort): void;
}