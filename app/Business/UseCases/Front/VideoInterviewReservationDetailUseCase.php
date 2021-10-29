<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Front\VideoInterviewReservationDetail\VideoInterviewReservationDetailInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\VideoInterviewReservationDetail\VideoInterviewReservationDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewReservationDetail\VideoInterviewReservationDetailInitializeOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\InterViewAppointment;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class VideoInterviewReservationDetailUseCase
 *
 * @package App\Business\UseCases\Front
 */
class VideoInterviewReservationDetailUseCase implements VideoInterviewReservationDetailInitializeInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * VideoInterviewListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param UserAccountRepository $userAccountRepository
     * @param InterviewAppointmentRepository $interviewAppointmentRepository
     */
    public function __construct(
        MemberRepository $memberRepository,
        InterviewAppointmentRepository $interviewAppointmentRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->interviewAppointmentRepository = $interviewAppointmentRepository;
    }

    /**
     * 初期表示
     *
     * @param VideoInterviewReservationDetailInitializeInputPort $inputPort
     * @param VideoInterviewReservationDetailInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(VideoInterviewReservationDetailInitializeInputPort $inputPort, VideoInterviewReservationDetailInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $interviewAppointmentId = $inputPort->interviewAppointmentId;

        // 面接予約取得
        $criteriaFactory = CriteriaFactory::getInstance();
        try {
            $interviewAppointment = $this->interviewAppointmentRepository->findOneByCriteria(
                $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $interviewAppointmentId,
                        "status" => [
                            InterviewAppointment::STATUS_RESERVATION,
                            InterviewAppointment::STATUS_CLOSE,
                        ]
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("select_target_not_found");
        }

        // 企業アカウント取得
        $companyUserAccount = $interviewAppointment->getCompanyUserAccount();
        $company = $companyUserAccount->getCompanyAccount()->getCompany();

        // 企業名
        $companyName = $company->getName();
        $outputPort->companyName = $companyName;

        // 企業業種
        $businessTypes = $company->getBusinessTypes();
        $businessTypeList = [];
        foreach ($businessTypes as $businessType) {
            $businessTypeName = $businessType->getName();
            $businessTypeList[] = $businessTypeName;
        }
        $formattedBusinessTypes = implode('/', $businessTypeList);
        $outputPort->formattedBusinessTypes = $formattedBusinessTypes;

        // 本社所在地
        $headOfficePrefecture = $company->getPrefecture()->getName();
        $outputPort->headOfficePrefecture = $headOfficePrefecture;

        // 企業紹介文
        $introductorySentence = $company->getIntroductorySentence();
        $outputPort->introductorySentence = $introductorySentence;

        // 予約日時
        $appointmentDatetime = $interviewAppointment->getAppointmentDatetime();
        setlocale(LC_ALL, 'ja_JP.UTF-8');
        $appointmentDate = $appointmentDatetime->formatLocalized('%Y年%m月%d日(%a)');
        $appointmentTime = $appointmentDatetime->format('H:i');
        $outputPort->appointmentDate = $appointmentDate;
        $outputPort->appointmentTime = $appointmentTime;

        // 面接URL
        $outputPort->videoInterviewRoomUrl = route("front.video-interview.room", ["interviewAppointmentId" => $interviewAppointmentId]);
        // 面接キャンセルURL
        $outputPort->videoInterviewCancelUrl = route("front.video-interview.cancel-confirm", ["interviewAppointmentId" => $interviewAppointmentId]);

        // 内容
        $content = $interviewAppointment->getContent();
        $outputPort->content = $content;

        //ログ出力
        Log::infoOut();
    }
}