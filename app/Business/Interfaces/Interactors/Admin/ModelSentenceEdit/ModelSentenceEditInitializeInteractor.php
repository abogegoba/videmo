<?php


namespace App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit;


interface ModelSentenceEditInitializeInteractor
{
    /**
     * @param ModelSentenceEditInitializeInputPort $inputPort
     * @param ModelSentenceEditInitializeOutputPort $outputPort
     */
    public function initialize(ModelSentenceEditInitializeInputPort $inputPort, ModelSentenceEditInitializeOutputPort $outputPort): void;
}