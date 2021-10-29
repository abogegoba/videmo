<?php


namespace App\Business\UseCases\Admin;


use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\ModelSentenceRepository;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceDelete\ModelSentenceDeleteInputPort;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceDelete\ModelSentenceDeleteInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceDelete\ModelSentenceDeleteOutputPort;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

class ModelSentenceDeleteUseCase implements ModelSentenceDeleteInteractor
{
    /**
     * @var ModelSentenceRepository
     */
    private $modelSentenceRepository;


    /**
     * ModelSentenceDeleteUseCase constructor.
     * @param ModelSentenceRepository $modelSentenceRepository
     */
    public function __construct(ModelSentenceRepository $modelSentenceRepository)
    {
        $this->modelSentenceRepository = $modelSentenceRepository;
    }

    /**
     * 削除する
     *
     * @param ModelSentenceDeleteInputPort $inputPort
     * @param ModelSentenceDeleteOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function destroy(ModelSentenceDeleteInputPort $inputPort, ModelSentenceDeleteOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 削除対象を取得する
        $modelSentenceId = $inputPort->modelSentenceId;
        try{
            $criteriaFactory = CriteriaFactory::getInstance();
            $modelSentence = $this->modelSentenceRepository->findOneByCriteria(
                $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $inputPort=$modelSentenceId
                    ]
                )
            );

        }catch (ObjectNotFoundException $e){
            // 削除対象が見つからなかった場合に例外
            throw new FatalBusinessException("delete_target_not_found");
        }

        $this->modelSentenceRepository->delete($modelSentence);

        // ログ出力
        Log::infoOut();
    }
}