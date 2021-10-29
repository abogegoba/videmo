<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\InterviewAppointmentSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\VideoCallHistorySearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\VideoCallHistoryRepository;
use App\Business\Interfaces\Interactors\Front\VideoInterviewList\VideoInterviewListInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\VideoInterviewList\VideoInterviewListInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewList\VideoInterviewListInitializeOutputPort;
use App\Business\Services\UseLoggedInMemberTrait;
use App\Domain\Entities\Company;
use App\Domain\Entities\InterViewAppointment;
use App\Domain\Entities\UserAccount;
use App\Domain\Entities\VideoCallHistory;
use Carbon\Carbon;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class VideoInterviewListUseCase
 *
 * @package App\Business\UseCases\Front
 */
class VideoInterviewListUseCase implements VideoInterviewListInitializeInteractor
{
    use UseLoggedInMemberTrait;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var VideoCallHistoryRepository
     */
    private $videoCallHistoryRepository;

    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * VideoInterviewListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     * @param VideoCallHistoryRepository $videoCallHistoryRepository
     * @param InterviewAppointmentRepository $interviewAppointmentRepository
     */
    public function __construct(
        MemberRepository $memberRepository,
        VideoCallHistoryRepository $videoCallHistoryRepository,
        InterviewAppointmentRepository $interviewAppointmentRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->videoCallHistoryRepository = $videoCallHistoryRepository;
        $this->interviewAppointmentRepository = $interviewAppointmentRepository;
    }

    /**
     * 初期表示
     *
     * @param VideoInterviewListInitializeInputPort $inputPort
     * @param VideoInterviewListInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(VideoInterviewListInitializeInputPort $inputPort, VideoInterviewListInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $member = $this->getLoggedInMember($inputPort);

        $loggedInUserAccount = $member->getUserAccount();
        $loggedInUserAccountId = $loggedInUserAccount->getId();

        // 面接予約取得
        $criteriaFactory = CriteriaFactory::getInstance();
        $interviewAppointments = $this->interviewAppointmentRepository->findByCriteria(
            $criteriaFactory->create(
                InterviewAppointmentSearchCriteria::class,
                GeneralExpressionBuilder::class,
                [
                    "memberUserAccount.id" => $loggedInUserAccountId
                ]
            )
        );

        $interviewAppointmentList = [];
        setlocale(LC_ALL, 'ja_JP.UTF-8');
        if (!empty($interviewAppointments)) {
            foreach ($interviewAppointments as $interviewAppointment) {
                $formattedAppointmentDatetime = $interviewAppointment->getAppointmentDatetime()->formatLocalized('%Y年%m月%d日(%a)');
                if (!array_key_exists($formattedAppointmentDatetime, $interviewAppointmentList)) {
                    $interviewAppointmentList[$formattedAppointmentDatetime] = $this->setInterViewAppointmentInformation(
                        $interviewAppointment,
                        $formattedAppointmentDatetime
                    );
                } else {
                    $formattedAppointmentDatetime = "";
                    $interviewAppointmentList[$interviewAppointment->getId()] = $this->setInterViewAppointmentInformation($interviewAppointment, $formattedAppointmentDatetime);
                }
            }
            $formattedInterviewAppointmentList = array_column($interviewAppointmentList, null, "id");
            $outputPort->formattedInterviewAppointmentList = $formattedInterviewAppointmentList;
        }

        // 面接履歴取得
        $videoCallHistories = $this->videoCallHistoryRepository->findByCriteria(
            $criteriaFactory->create(
                VideoCallHistorySearchCriteria::class,
                GeneralExpressionBuilder::class,
                [
                    "memberUserAccount.id" => $loggedInUserAccountId
                ]
            )
        );
        $videoCallHistoryList = [];
        if (!empty($videoCallHistories)) {
            foreach ($videoCallHistories as $videoCallHistorie) {
                $id = $videoCallHistorie->getId();
                $companyUserAccount = $videoCallHistorie->getCompanyUserAccount();
                $company = $companyUserAccount->getCompanyAccount()->getCompany();
                $startDatetime = $videoCallHistorie->getStartDatetime();
                setlocale(LC_ALL, 'ja_JP.UTF-8');
                $formattedStartDateTime = $startDatetime->formatLocalized('%Y年%m月%d日(%a)');
                if (!array_key_exists($formattedStartDateTime, $videoCallHistoryList)) {
                    $videoCallHistoryList[$formattedStartDateTime] = $this->setVideoCallHistoryInformation($company, $companyUserAccount, $videoCallHistorie, $formattedStartDateTime, $id);
                } else {
                    $formattedStartDateTime = "";
                    $videoCallHistoryList[$id] = $this->setVideoCallHistoryInformation($company, $companyUserAccount, $videoCallHistorie, $formattedStartDateTime, $id);
                }
            }
            $formattedVideoCallHistoryList = array_column($videoCallHistoryList, null, "id");
            $outputPort->formattedVideoCallHistoryList = $formattedVideoCallHistoryList;
        }

        // ログ出力
        Log::infoOut();
    }

    /**
     * 面接予約情報を取得
     *
     * @param InterViewAppointment $interviewAppointment
     * @param String $formattedStartDateTime
     * @return array
     */
    private function setInterViewAppointmentInformation(InterViewAppointment $interviewAppointment, string $formattedStartDateTime)
    {
        $id = $interviewAppointment->getId();
        $company = $interviewAppointment->getCompanyUserAccount()->getCompanyAccount()->getCompany();
        $user_id = $interviewAppointment->getCompanyUserAccount()->getId();
        return [
            'id' => $id,
            'companyDetailUrl' => route("front.company.detail", ["userAccountId" => $user_id]),
            'videoInterviewReservationDetailUrl' => route("front.video-interview.reservation-detail", ["interviewAppointmentId" => $id]),
            'videoInterviewCancelUrl' => route("front.video-interview.cancel-confirm", ["interviewAppointmentId" => $id]),
            'companyName' => $company->getName(),
            'status' => $interviewAppointment->getStatus(),
            'formattedAppointmentDatetime' => $formattedStartDateTime,
            'appointmentYear' => $interviewAppointment->getAppointmentDatetime()->format('Y'),
            'appointmentMonth' => $interviewAppointment->getAppointmentDatetime()->format('n'),
            'appointmentDay' => $interviewAppointment->getAppointmentDatetime()->format('j'),
            'appointmentDate' => $interviewAppointment->getAppointmentDatetime()->format('w'),
            'appointmentTime' => $interviewAppointment->getAppointmentDatetime()->format('H:i')
        ];
    }

    /**
     * ビデオ通話履歴情報を取得
     *
     * @param Company $company
     * @param UserAccount $companyUserAccount
     * @param VideoCallHistory $videoCallHistory
     * @param string $formattedStartDateTime
     * @param int $id
     * @return array
     */
    private function setVideoCallHistoryInformation(Company $company, UserAccount $companyUserAccount, VideoCallHistory $videoCallHistory, string $formattedStartDateTime, int $id)
    {
        $user_id = $companyUserAccount->getId();
        $startDatetime = $videoCallHistory->getStartDatetime();

        return [
            'id' => $id,
            'companyDetailUrl' => route("front.company.detail", ["userAccountId" => $user_id]),
            'companyLogo' => $company->getCompanyLogoImage()->getFilePathForFrontShow(),
            'companyName' => $company->getName(),
            'companyUserAccountName' => $companyUserAccount->getCompanyAccount()->getLastName(),
            'startYear' => $startDatetime->format('Y'),
            'startMonth' => $startDatetime->format('n'),
            'startDay' => $startDatetime->format('j'),
            'startDate' => $startDatetime->format('w'),
            'startTime' => $startDatetime->format('H:i'),
            'formattedStartDateTime' => $formattedStartDateTime
        ];
    }
}
