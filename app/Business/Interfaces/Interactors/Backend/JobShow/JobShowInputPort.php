<?php

namespace App\Business\Interfaces\Interactors\Backend\JobShow;

use App\Business\Interfaces\Interactors\Backend\JobStore\JobStoreOutputPort;

/**
 * Interface JobShowInputPort
 *
 * @package App\Business\Interfaces\Interactors\Backend\JobShow
 *
 * @property int $jobId ジョブID
 */
interface JobShowInputPort extends JobStoreOutputPort
{
}