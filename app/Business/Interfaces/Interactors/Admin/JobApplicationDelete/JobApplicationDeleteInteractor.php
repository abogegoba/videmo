<?php


namespace App\Business\Interfaces\Interactors\Admin\JobApplicationDelete;


interface JobApplicationDeleteInteractor
{
    /**
     * 削除する
     *
     * @param JobApplicationDeleteInputPort $inputPort
     * @param JobApplicationDeleteOutputPort $outputPort
     */
    public function destroy(JobApplicationDeleteInputPort $inputPort, JobApplicationDeleteOutputPort $outputPort): void;
}