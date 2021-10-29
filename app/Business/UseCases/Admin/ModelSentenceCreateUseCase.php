<?php


namespace App\Business\UseCases\Admin;


use App\Business\Interfaces\Gateways\Repositories\ModelSentenceRepository;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate\ModelSentenceCreateInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate\ModelSentenceCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate\ModelSentenceCreateInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate\ModelSentenceCreateStoreInputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate\ModelSentenceCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate\ModelSentenceCreateStoreOutputPort;
use App\Domain\Entities\ModelSentence;
use App\Utilities\Log;
use ReLab\Commons\Wrappers\Data;

class ModelSentenceCreateUseCase implements ModelSentenceCreateInitializeInteractor, ModelSentenceCreateStoreInteractor
{
    /**
     * @var ModelSentenceRepository
     */
    private $modelSentenceRepository;


    /**
     * ModelSentenceCreateUseCase constructor.
     * @param ModelSentenceRepository $modelSentenceRepository
     */
    public function __construct(ModelSentenceRepository $modelSentenceRepository)
    {
        $this->modelSentenceRepository = $modelSentenceRepository;
    }


    /**
     * 初期化する
     *
     * @param ModelSentenceCreateInitializeInputPort $inputPort
     * @param ModelSentenceCreateInitializeOutputPort $outputPort
     */
    public function initialize(ModelSentenceCreateInitializeInputPort $inputPort, ModelSentenceCreateInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $typeList = ModelSentence::TYPE_LIST;
        $outputPort->modelSentenceTypeList = $typeList;

        // ログ出力
        Log::infoOut();
    }


    /**
     * 登録する
     *
     * @param ModelSentenceCreateStoreInputPort $inputPort
     * @param ModelSentenceCreateStoreOutputPort $outputPort
     */
    public function store(ModelSentenceCreateStoreInputPort $inputPort, ModelSentenceCreateStoreOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $modelSentence = new ModelSentence();

        Data::mappingToObject($inputPort,$modelSentence);

        // 例文保存
        $this->modelSentenceRepository->saveOrUpdate($modelSentence,true);

        // 登録した例文IDを取得
        $outputPort->modelSentenceId = $modelSentence->getId();

        // ログ出力
        Log::infoOut();
    }
}