<?php


namespace App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate;


interface ModelSentenceCreateStoreInteractor
{
    /**
     * 登録する
     *
     * @param ModelSentenceCreateStoreInputPort $inputPort
     * @param ModelSentenceCreateStoreOutputPort $outputPort
     */
    public function store(ModelSentenceCreateStoreInputPort $inputPort, ModelSentenceCreateStoreOutputPort $outputPort): void;
}