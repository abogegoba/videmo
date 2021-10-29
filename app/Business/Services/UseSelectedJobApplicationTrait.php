<?php


namespace App\Business\Services;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Interactors\Client\Common\UseSelectedJobApplicationInputPort;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Trait UseSelectedJobApplicationTrait
 *
 * 選択した求人情報取得トレイト(UseCase専用)
 *
 * @package App\Business\Services
 * @throws FatalBusinessException
 */
trait UseSelectedJobApplicationTrait
{
    protected function getSelectedJobApplication(UseSelectedJobApplicationInputPort $inputPort)
    {
        try{
        $selectedJobApplication = $this->jobApplicationRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->selectedJobApplicationsId,
                ]
            )
        );
        }catch (ObjectNotFoundException $e){
            throw new FatalBusinessException('');
        }
        return $selectedJobApplication;
    }
}