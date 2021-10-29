<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentDetail\InterviewAppointmentDetailShowInputPort;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentDetail\InterviewAppointmentDetailShowInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentDetail\InterviewAppointmentDetailShowOutputPort;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class InterviewAppointmentDetailUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class InterviewAppointmentDetailUseCase implements InterviewAppointmentDetailShowInteractor
{
    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * MemberDeleteUseCase constructor.
     *
     * @param InterviewAppointmentRepository $interviewAppointmentRepository
     */
    public function __construct(
        InterviewAppointmentRepository $interviewAppointmentRepository
    ) {
        $this->interviewAppointmentRepository = $interviewAppointmentRepository;
    }

    /**
     * 参照する
     *
     * @param InterviewAppointmentDetailShowInputPort $inputPort
     * @param InterviewAppointmentDetailShowOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function show(InterviewAppointmentDetailShowInputPort $inputPort, InterviewAppointmentDetailShowOutputPort $outputPort): void
    {
        try {
            // 参照対象を取得する
            $outputPort->interviewAppointment = $this->interviewAppointmentRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" =>  $inputPort->interviewAppointmentId
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("data_not_found");
        }
    }
}