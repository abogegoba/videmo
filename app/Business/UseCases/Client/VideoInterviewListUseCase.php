<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\InterviewAppointmentSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\VideoCallHistorySearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Gateways\Repositories\VideoCallHistoryRepository;
use App\Business\Interfaces\Interactors\Client\VideoInterviewList\VideoInterviewListInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewList\VideoInterviewListInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewList\VideoInterviewListInitializeOutputPort;
use App\Business\Services\UseLoggedInCompanyAccountTrait;
use App\Domain\Entities\InterViewAppointment;
use App\Domain\Entities\UserAccount;
use App\Domain\Entities\VideoCallHistory;
use Carbon\Carbon;
use App\Utilities\Log;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class VideoInterviewListUseCase
 *
 * @package App\Business\UseCases\Client
 */
class VideoInterviewListUseCase implements VideoInterviewListInitializeInteractor
{
    use UseLoggedInCompanyAccountTrait;

    /**
     * @var CompanyAccountRepository
     */
    private $companyAccountRepository;

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
     * @param CompanyAccountRepository $companyAccountRepository
     * @param VideoCallHistoryRepository $videoCallHistoryRepository
     * @param InterviewAppointmentRepository $interviewAppointmentRepository
     */
    public function __construct(
        CompanyAccountRepository $companyAccountRepository,
        VideoCallHistoryRepository $videoCallHistoryRepository,
        InterviewAppointmentRepository $interviewAppointmentRepository
    ) {
        $this->companyAccountRepository = $companyAccountRepository;
        $this->videoCallHistoryRepository = $videoCallHistoryRepository;
        $this->interviewAppointmentRepository = $interviewAppointmentRepository;
    }

    /**
     * 初期表示
     *
     * @param VideoInterviewListInitializeInputPort $inputPort
     * @param VideoInterviewListInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewListInitializeInputPort $inputPort, VideoInterviewListInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $companyAccount = $this->getLoggedInCompanyAccount($inputPort);

        $loggedInUserAccount = $companyAccount->getUserAccount();
        $loggedInUserAccountId = $loggedInUserAccount->getId();

        // 面接予約取得
        $criteriaFactory = CriteriaFactory::getInstance();
        $interviewAppointments = $this->interviewAppointmentRepository->findByCriteria(
            $criteriaFactory->create(
                InterviewAppointmentSearchCriteria::class,
                GeneralExpressionBuilder::class,
                [
                    "companyUserAccount.id" => $loggedInUserAccountId
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
        $criteriaFactory = CriteriaFactory::getInstance();
        $videoCallHistories = $this->videoCallHistoryRepository->findByCriteria(
            $criteriaFactory->create(
                VideoCallHistorySearchCriteria::class,
                GeneralExpressionBuilder::class,
                [
                    "companyUserAccount.id" => $loggedInUserAccountId
                ]
            )
        );
        $videoCallHistoryList = [];
        if (!empty($videoCallHistories)) {
            foreach ($videoCallHistories as $videoCallHistorie) {
                $id = $videoCallHistorie->getId();
                $memberUserAccount = $videoCallHistorie->getMemberUserAccount();
                // $member = $memberUserAccount->getMember();
                $startDatetime = $videoCallHistorie->getStartDatetime();
                setlocale(LC_ALL, 'ja_JP.UTF-8');
                $formattedStartDateTime = $startDatetime->formatLocalized('%Y年%m月%d日(%a)');
                if (!array_key_exists($formattedStartDateTime, $videoCallHistoryList)) {
                    $videoCallHistoryList[$formattedStartDateTime] = $this->setVideoCallHistoryInformation($memberUserAccount, $videoCallHistorie, $formattedStartDateTime, $id);
                } else {
                    $formattedStartDateTime = "";
                    $videoCallHistorieList[$id] = $this->setVideoCallHistoryInformation($memberUserAccount, $videoCallHistorie, $formattedStartDateTime, $id);
                }
            }
            $formattedVideoCallHistoryList = array_column($videoCallHistoryList, null, "id");
            $outputPort->formattedVideoCallHistoryList = $formattedVideoCallHistoryList;
        }

        //ログ出力
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
        $member = $interviewAppointment->getMemberUserAccount()->getMember();
        $user_id = $interviewAppointment->getMemberUserAccount()->getId();
        return [
            'id' => $id,
            'studentDetailUrl' => route("client.student.detail", ["userAccountId" => $user_id]),
            'videoInterviewReservationDetailUrl' => route("client.video-interview.reservation-detail", ["interviewAppointmentId" => $id]),
            'videoInterviewCancelUrl' => route("client.video-interview.cancel-confirm", ["interviewAppointmentId" => $id]),
            'memberName' => $member->getLastName() ."　".$member->getFirstName(),
            'schoolName' => $member->getOldSchool()->getName(),
            'departmentName' => $member->getOldSchool()->getDepartmentName(),
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
     * @param MemberUserAccount $memberUserAccount
     * @param VideoCallHistory $videoCallHistory
     * @param string $formattedStartDateTime
     * @param int $id
     * @return array
     */
    private function setVideoCallHistoryInformation(UserAccount $memberUserAccount, VideoCallHistory $videoCallHistory, string $formattedStartDateTime, int $id)
    {
        $member = $memberUserAccount->getMember();
        $user_id = $memberUserAccount->getId();
        $startDatetime = $videoCallHistory->getStartDatetime();

        return [
            'id' => $id,
            'studentDetailUrl' => route("client.student.detail", ["userAccountId" => $user_id]),
            'idImage' => $member->getIdPhotoFilePathForClientShow(),
            'memberLastName' => $member->getLastName(),
            'memberFirstName' => $member->getFirstName(),
            'startYear' => $startDatetime->format('Y'),
            'startMonth' => $startDatetime->format('n'),
            'startDay' => $startDatetime->format('j'),
            'startDate' => $startDatetime->format('w'),
            'startTime' => $startDatetime->format('H:i'),
            'formattedStartDateTime' => $formattedStartDateTime
        ];
    }
}
