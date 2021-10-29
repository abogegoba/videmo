<?php


namespace App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit;


interface ModelSentenceEditUpdateInteractor
{
    /**
     * @param ModelSentenceEditUpdateInputPort $inputPort
     * @param ModelSentenceEditUpdateOutputPort $outputPort
     */
    public function update(ModelSentenceEditUpdateInputPort $inputPort, ModelSentenceEditUpdateOutputPort $outputPort): void;
}