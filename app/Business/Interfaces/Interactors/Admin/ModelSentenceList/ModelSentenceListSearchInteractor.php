<?php


namespace App\Business\Interfaces\Interactors\Admin\ModelSentenceList;

/**
 * Interface ModelSentenceListSearchInteractor
 * @package App\Business\Interfaces\Interactors\Admin\ModelSentenceList
 */
interface ModelSentenceListSearchInteractor
{
    /**
     * @param ModelSentenceListSearchInputPort $inputPort
     * @param ModelSentenceListSearchOutputPort $outputPort
     */
    public function search(ModelSentenceListSearchInputPort $inputPort, ModelSentenceListSearchOutputPort $outputPort): void;
}