<?php


namespace App\Business\UseCases\Admin;


use App\Business\Interfaces\Gateways\Criteria\ModelSentenceListSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\ModelSentenceListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\ModelSentenceRepository;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceList\ModelSentenceListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceList\ModelSentenceListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceList\ModelSentenceListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceList\ModelSentenceListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceList\ModelSentenceListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceList\ModelSentenceListSearchOutputPort;
use App\Domain\Entities\ModelSentence;
use App\Utilities\Log;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

class ModelSentenceListUseCase implements ModelSentenceListInitializeInteractor, ModelSentenceListSearchInteractor
{
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var ModelSentenceRepository
     */
    private $modelSentenceRepository;


    public function __construct(ModelSentenceRepository $modelSentenceRepository)
    {
        $this->modelSentenceRepository = $modelSentenceRepository;
    }

    /**
     * 初期化する
     *
     * @param ModelSentenceListInitializeInputPort $inputPort
     * @param ModelSentenceListInitializeOutputPort $outputPort
     */
    public function initialize(ModelSentenceListInitializeInputPort $inputPort, ModelSentenceListInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $typeList = ModelSentence::TYPE_LIST;
        $outputPort->typeList = $typeList;

        // ログ出力
        Log::infoOut();
    }


    /**
     * 検索する
     *
     * @param ModelSentenceListSearchInputPort $inputPort
     * @param ModelSentenceListSearchOutputPort $outputPort
     */
    public function search(ModelSentenceListSearchInputPort $inputPort, ModelSentenceListSearchOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // Inputにページ指定が存在しない場合は新規で作成する
        $pager = $inputPort->pager;
        if (!isset($pager)) {
            $pager = new Class() extends Data implements Pager
            {
            };
        }
        // 1ページ最大件数を設定する
        $pager->limit = self::PAGE_LIMIT_COUNT;
        $outputPort->pager = $pager;

        $modelSentence = $this->modelSentenceRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(ModelSentenceListSearchCriteria::class, ModelSentenceListSearchExpressionBuilder::class,
                $inputPort,
                [
                    "pager" => $pager
                ]
            )
        );
        $outputPort->modelSentences = $modelSentence;

        // ログ出力
        Log::infoOut();
    }
}