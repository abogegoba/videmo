<?php


namespace App\Business\Interfaces\Interactors\Admin\ModelSentenceDelete;


interface ModelSentenceDeleteInteractor
{
    /**
     * 削除する
     *
     * @param ModelSentenceDeleteInputPort $inputPort
     * @param ModelSentenceDeleteOutputPort $outputPort
     */
    public function destroy(ModelSentenceDeleteInputPort $inputPort, ModelSentenceDeleteOutputPort $outputPort): void;
}