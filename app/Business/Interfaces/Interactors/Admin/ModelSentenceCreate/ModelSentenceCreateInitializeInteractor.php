<?php

namespace App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate;

/**
 * Interface ModelSentenceCreateInitializeInteractor
 *
 * @package App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate
 */
interface ModelSentenceCreateInitializeInteractor
{
    /**
     * 初期化する
     *
     * @param ModelSentenceCreateInitializeInputPort $inputPort
     * @param ModelSentenceCreateInitializeOutputPort $outputPort
     */
    public function initialize(ModelSentenceCreateInitializeInputPort $inputPort, ModelSentenceCreateInitializeOutputPort $outputPort): void;
}