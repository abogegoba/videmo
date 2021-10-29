<?php


namespace App\Business\Interfaces\Interactors\Admin\MessageDetail;

/**
 * Interface AdminMessageStatusUpdateInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\MessageDetail
 */
interface AdminMessageStatusUpdateInteractor
{
    /**
     * @param AdminMessageStatusUpdateInputPort $inputPort
     * @param AdminMessageStatusUpdateOutputPort $outputPort
     */
    public function update(AdminMessageStatusUpdateInputPort $inputPort, AdminMessageStatusUpdateOutputPort $outputPort): void;
}