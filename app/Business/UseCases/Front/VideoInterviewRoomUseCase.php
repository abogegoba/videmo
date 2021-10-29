<?php

namespace App\Business\UseCases\Front;


use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\VideoCallHistoryRepository;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomEndInputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomEndInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomEndOutputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomStartInputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomStartInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomStartOutputPort;
use App\Business\Interfaces\Interactors\Front\VideoInterviewRoom\VideoInterviewRoomInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\VideoInterviewRoom\VideoInterviewRoomInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewRoom\VideoInterviewRoomInitializeOutputPort;
use App\Domain\Entities\InterViewAppointment;
use App\Domain\Entities\UserAccount;
use App\Domain\Entities\VideoCallHistory;
use App\Domain\Model\MemberAuthentication;
use App\Utilities\Log;
use Carbon\Carbon;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Transaction;

/**
 * Class VideoInterviewRoomUseCase
 *
 * @package App\Business\UseCases\Front
 */
class VideoInterviewRoomUseCase implements VideoInterviewRoomInitializeInteractor, VideoInterviewRoomStartInteractor, VideoInterviewRoomEndInteractor
{
    /**_
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * @var VideoCallHistoryRepository
     */
    private $videoCallHistoryRepository;

    /**
     * VideoInterviewRoomUseCase constructor.
     *
     * @param UserAccountRepository $userAccountRepository
     * @param InterviewAppointmentRepository $interviewAppointmentRepository
     * @param VideoCallHistoryRepository $videoCallHistoryRepository
     */
    public function __construct(UserAccountRepository $userAccountRepository,
                                InterviewAppointmentRepository $interviewAppointmentRepository,
                                VideoCallHistoryRepository $videoCallHistoryRepository)
    {
        $this->userAccountRepository = $userAccountRepository;
        $this->interviewAppointmentRepository = $interviewAppointmentRepository;
        $this->videoCallHistoryRepository = $videoCallHistoryRepository;
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

        $interviewAppointment = $this->findInterviewAppointmentById($inputPort->interviewAppointmentId);
        $outputPort->companyPeerId = $interviewAppointment->getCompanyPeerId();
        $outputPort->memberPeerId = $interviewAppointment->getMemberPeerId();

        //ログ出力
        Log::infoOut();
    }

    /**
     * ビデオを開始する
     *
     * @param VideoInterviewRoomStartInputPort $inputPort
     * @param VideoInterviewRoomStartOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function startVideo(VideoInterviewRoomStartInputPort $inputPort, VideoInterviewRoomStartOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // ビデオ面接履歴を作成する
        $interviewAppointment = $this->findInterviewAppointmentById($inputPort->interviewAppointmentId);
        $memberAuthentication = MemberAuthentication::loadSession();
        $memberUserAccount = $this->userAccountRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $memberAuthentication->getUserAccountId()
                ]
            )
        );
        $videoCallHistory = new VideoCallHistory();
        $videoCallHistory->setMemberUserAccount($memberUserAccount);
        $videoCallHistory->setCompanyUserAccount($interviewAppointment->getCompanyUserAccount());

        // ビデオ面接履歴に面接開始日時を登録する
        $videoCallHistory->setStartDatetime(Transaction::getInstance()->getDateTime());
        $this->videoCallHistoryRepository->saveOrUpdate($videoCallHistory, true);

        // 登録結果を返却する
        $outputPort->videoCallHistoryId = $videoCallHistory->getId();

        //ログ出力
        Log::infoOut();
    }

    /**
     * ビデオを終了する
     *
     * @param VideoInterviewRoomEndInputPort $inputPort
     * @param VideoInterviewRoomEndOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function endVideo(VideoInterviewRoomEndInputPort $inputPort, VideoInterviewRoomEndOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // ビデオ面接履歴を取得する
        try {
            $memberAuthentication = MemberAuthentication::loadSession();
            $interviewAppointment = $this->findInterviewAppointmentById($inputPort->interviewAppointmentId, true);
            $videoCallHistory = $this->videoCallHistoryRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $inputPort->videoCallHistoryId,
                        "memberUserAccount.id" => $memberAuthentication->getUserAccountId()
                    ],
                    [
                        "forUpdate" => true
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("select_target_not_found");
        }
        // 面談のステータスを終了済みにupdateする
        $interviewAppointment->setStatus(InterviewAppointment::STATUS_CLOSE);
        $this->interviewAppointmentRepository->saveOrUpdate($interviewAppointment, true);

        // ビデオ面接履歴に面接時間（分）を登録する
        $videoCallHistory->setCallMinutes($videoCallHistory->getStartDatetime()->diffInMinutes(Transaction::getInstance()->getDateTime()));
        $this->videoCallHistoryRepository->saveOrUpdate($videoCallHistory, true);

        // 登録結果を返却する
        $outputPort->callMinutes = $videoCallHistory->getCallMinutes();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 面談履歴を取得する
     *
     * @param int $interviewAppointmentId
     * @param int $forUpdate
     * @return InterviewAppointment
     * @throws FatalBusinessException
     */
    private function findInterviewAppointmentById(int $interviewAppointmentId, bool $forUpdate = false): InterViewAppointment
    {
        $interviewAppointment = null;
        try {
            $memberAuthentication = MemberAuthentication::loadSession();
            $interviewAppointment = $this->interviewAppointmentRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $interviewAppointmentId,
                        "memberUserAccount.id" => $memberAuthentication->getUserAccountId()
                    ],
                    [
                        "forUpdate" => $forUpdate
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("select_target_not_found");
        }
        return $interviewAppointment;
    }
}