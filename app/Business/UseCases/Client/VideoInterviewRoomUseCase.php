<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomInitializeOutputPort;
use App\Domain\Model\ClientAuthentication;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class VideoInterviewRoomUseCase
 *
 * @package App\Business\UseCases\Client
 */
class VideoInterviewRoomUseCase implements VideoInterviewRoomInitializeInteractor
{
    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * VideoInterviewRoomUseCase constructor.
     *
     * @param InterviewAppointmentRepository $interviewAppointmentRepository
     */
    public function __construct(InterviewAppointmentRepository $interviewAppointmentRepository)
    {
        $this->interviewAppointmentRepository = $interviewAppointmentRepository;
    }

    /**
     * 初期表示
     *
     * @param VideoInterviewRoomInitializeInputPort $inputPort
     * @param VideoInterviewRoomInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(VideoInterviewRoomInitializeInputPort $inputPort, VideoInterviewRoomInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        try {
            $clientAuthentication = ClientAuthentication::loadSession();
            $interviewAppointment = $this->interviewAppointmentRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $inputPort->interviewAppointmentId,
                        "companyUserAccount.id" => $clientAuthentication->getUserAccountId()
                    ]
                )
            );
            $outputPort->companyPeerId = $interviewAppointment->getCompanyPeerId();
            $outputPort->memberPeerId = $interviewAppointment->getMemberPeerId();
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("select_target_not_found");
        }

        //ログ出力
        Log::infoOut();
    }
}