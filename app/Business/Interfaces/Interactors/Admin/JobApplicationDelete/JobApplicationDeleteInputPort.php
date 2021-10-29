<?php


namespace App\Business\Interfaces\Interactors\Admin\JobApplicationDelete;


use App\Business\Interfaces\Interactors\Client\Common\UseSelectedJobApplicationInputPort;

/**
 * Interface JobApplicationDeleteInputPort
 *
 * @package App\Business\Interfaces\Interactors\Admin\JobApplicationDelete
 * @property int $jobApplicationId 求人ID
 */
interface JobApplicationDeleteInputPort extends UseSelectedJobApplicationInputPort
{
}