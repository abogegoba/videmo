<?php


namespace App\Business\Interfaces\Interactors\Admin\ModelSentenceList;


interface ModelSentenceListInitializeInteractor
{
    /**
     * @param ModelSentenceListInitializeInputPort $inputPort
     * @param ModelSentenceListInitializeOutputPort $outputPort
     */
    public function initialize(ModelSentenceListInitializeInputPort $inputPort, ModelSentenceListInitializeOutputPort $outputPort): void;
}