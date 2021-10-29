<?php


namespace App\Business\UseCases\Admin;


use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\ModelSentenceRepository;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit\ModelSentenceEditInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit\ModelSentenceEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit\ModelSentenceEditInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit\ModelSentenceEditUpdateInputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit\ModelSentenceEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit\ModelSentenceEditUpdateOutputPort;
use App\Domain\Entities\ModelSentence;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

class ModelSentenceEditUseCase implements ModelSentenceEditInitializeInteractor, ModelSentenceEditUpdateInteractor
{
    /**
     * @var ModelSentenceRepository
     */
    private $modelSentenceRepository;

    /**
     * ModelSentenceEditUseCase constructor.
     * @param ModelSentenceRepository $modelSentenceRepository
     */
    public function __construct(ModelSentenceRepository $modelSentenceRepository)
    {
        $this->modelSentenceRepository = $modelSentenceRepository;
    }


    /**
     * 初期化する
     *
     * @param ModelSentenceEditInitializeInputPort $inputPort
     * @param ModelSentenceEditInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(ModelSentenceEditInitializeInputPort $inputPort, ModelSentenceEditInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $typeList = ModelSentence::TYPE_LIST;
        $outputPort->modelSentenceTypeList = $typeList;

        // 変更対象の例文を取得
        $modelSentenceId = $inputPort->modelSentenceId;
        try{
            $criteriaFactory = CriteriaFactory::getInstance();
            $modelSentence = $this->modelSentenceRepository->findOneByCriteria(
                $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        'id' => $modelSentenceId
                    ])
            );
        }catch (ObjectNotFoundException $e){
            // 変更対象が見つからない場合に例外
            throw new FatalBusinessException("edi_target_not_found");
        }

        $outputPort->modelSentence = $modelSentence;

        // ログ出力
        Log::infoOut();
    }

    /**
     * @param ModelSentenceEditUpdateInputPort $inputPort
     * @param ModelSentenceEditUpdateOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function update(ModelSentenceEditUpdateInputPort $inputPort, ModelSentenceEditUpdateOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 変更対象の例文を取得
        $modelSentenceId = $inputPort->modelSentenceId;
        try{
            $criteriaFactory = CriteriaFactory::getInstance();
            $modelSentence = $this->modelSentenceRepository->findOneByCriteria(
                $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        'id' => $modelSentenceId
                    ])
            );
        }catch (ObjectNotFoundException $e){
            // 変更対象が見つからない場合に例外
            throw new FatalBusinessException("edi_target_not_found");
        }

        Data::mappingToObject($inputPort, $modelSentence);

        $this->modelSentenceRepository->saveOrUpdate($modelSentence, true);

        // ログ出力
        Log::infoOut();
    }
}