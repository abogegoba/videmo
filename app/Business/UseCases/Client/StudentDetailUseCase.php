<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Client\StudentDetail\StudentDetailInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\StudentDetail\StudentDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\StudentDetail\StudentDetailInitializeOutputPort;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class StudentDetailUseCase
 *
 * @package App\Business\UseCases\Client
 */
class StudentDetailUseCase implements StudentDetailInitializeInteractor
{
    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * MessageDetailUseCase constructor.
     *
     * @param UserAccountRepository $userAccountRepository
     */
    public function __construct(
        UserAccountRepository $userAccountRepository
    ) {
        $this->userAccountRepository = $userAccountRepository;
    }

    /**
     * 初期表示
     *
     * @param StudentDetailInitializeInputPort $inputPort
     * @param StudentDetailInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(StudentDetailInitializeInputPort $inputPort, StudentDetailInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        try {
            $userAccount = $this->userAccountRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $inputPort->memberUserAccountId,
                    ]
                )
            );
        }catch (ObjectNotFoundException $e){
            throw new FatalBusinessException("data_not_found");
        }

        $member = $userAccount->getMember();
        $outputPort->member = $member;

        $prVideos = [];
        foreach ($member->getPrVideos() as $prVideo) {
            $prVideos[] = [
                'prVideoPath' => $prVideo->getFilePathForClientShow(),
                'title' => $prVideo->getTitle(),
                'description' => $prVideo->getDescription(),
            ];
        }
        $outputPort->prVideos = $prVideos;

        // ログ出力
        Log::infoOut();
    }
}
