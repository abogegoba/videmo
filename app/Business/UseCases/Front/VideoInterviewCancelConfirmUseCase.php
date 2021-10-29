<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm\VideoInterviewCancelExecuteInputPort;
use App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm\VideoInterviewCancelExecuteInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm\VideoInterviewCancelConfirmInitializeInputPort;
use App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm\VideoInterviewCancelConfirmInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm\VideoInterviewCancelConfirmInitializeOutputPort;
use App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm\VideoInterviewCancelExecuteOutputPort;
use App\Domain\Entities\InterViewAppointment;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;
use ReLab\Doctrine\Criteria\GeneralDoctrineCriteria;

/**
 * Class VideoInterviewCancelConfirmUseCase
 *
 * @package App\Business\UseCases\Front
 */
class VideoInterviewCancelConfirmUseCase implements VideoInterviewCancelConfirmInitializeInteractor, VideoInterviewCancelExecuteInteractor
{
    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * VideoInterviewListUseCase constructor.
     *
     * @param InterviewAppointmentRepository $interviewAppointmentRepository
     */
    public function __construct(
        InterviewAppointmentRepository $interviewAppointmentRepository
    ) {
        $this->interviewAppointmentRepository = $interviewAppointmentRepository;
    }

    /**
     * 初期表示
     *
     * @param VideoInterviewCancelConfirmInitializeInputPort $inputPort
     * @param VideoInterviewCancelConfirmInitializeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function initialize(VideoInterviewCancelConfirmInitializeInputPort $inputPort, VideoInterviewCancelConfirmInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        $interviewAppointmentId = $inputPort->interviewAppointmentId;
        $interviewAppointment = $this->getInterviewAppointment($interviewAppointmentId);

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

        // 内容
        $content = $interviewAppointment->getContent();
        $outputPort->content = $content;

        // 面接一覧URL
        $outputPort->videoInterviewListUrl = route("front.video-interview.list", ["interviewAppointmentId" => $interviewAppointmentId]);
        // 面接キャンセルURL
        $outputPort->videoInterviewCancelUrl = route("front.video-interview.cancel-confirm", ["interviewAppointmentId" => $interviewAppointmentId]);

        $outputPort->interviewAppointmentId = $interviewAppointmentId;

        //ログ出力
        Log::infoOut();
    }

    /**
     * キャンセル実行
     *
     * @param VideoInterviewCancelExecuteInputPort $inputPort
     * @param VideoInterviewCancelExecuteOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function execute(VideoInterviewCancelExecuteInputPort $inputPort, VideoInterviewCancelExecuteOutputPort $outputPort): void
    {
        Log::infoIn();

        $interviewAppointmentId = $inputPort->interviewAppointmentId;
        $interviewAppointment = $this->getInterviewAppointment($interviewAppointmentId);

        // ステータスをキャンセルにする
        $status = $interviewAppointment->getStatus();
        if ($status === InterviewAppointment::STATUS_RESERVATION) {
            $interviewAppointment->setStatus(InterviewAppointment::STATUS_CANCEL);
            $cancelMessage = $inputPort->cancelMessage;
            $interviewAppointment->setCancelMessage($cancelMessage);
            $this->interviewAppointmentRepository->saveOrUpdate($interviewAppointment, true);

            // メール送信
            $template = 'mail.front.member.interview_appointment_cancel_mail';
            $companyUserAccount = $interviewAppointment->getCompanyUserAccount();
            $mailAddress = $companyUserAccount->getMailAddress();
            $companyAccount = $companyUserAccount->getCompanyAccount();
            $memberUserAccount = $interviewAppointment->getMemberUserAccount();
            $member = $memberUserAccount->getMember();
            $title = '【LinkT】面接キャンセルのご案内';
            $dataList['companyAccount'] = $companyAccount;
            $dataList['member'] = $member;
            $dataList['appointmentDatetime'] = $interviewAppointment->getAppointmentDatetime()->format('Y年n月j日 H時i分');
            $frontAppURL = env('FRONT_APP_URL');
            $contactURL = "$frontAppURL/mypage/contact";
            $dataList['contactURL'] = $contactURL;
            $dataList['cancelMessage'] = $cancelMessage;
            $data = Data::wrap($dataList);
            $mail = Mail::getInstance($template, $mailAddress, trans($title), $data);
            $mailResult = $mail->send();

            if ($mailResult !== true) {
                throw new FatalBusinessException("not_send_mail");
            }
        }

        //ログ出力
        Log::infoOut();
    }

    /**
     * 面接予約取得
     *
     * @param int $interviewAppointmentId
     * @return InterViewAppointment
     * @throws FatalBusinessException
     */
    private function getInterviewAppointment(int $interviewAppointmentId)
    {
        $criteriaFactory = CriteriaFactory::getInstance();
        try {
            $interviewAppointment = $this->interviewAppointmentRepository->findOneByCriteria(
                $criteriaFactory->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $interviewAppointmentId,
                        "status" => InterviewAppointment::STATUS_RESERVATION
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new FatalBusinessException("select_target_not_found");
        }
        return $interviewAppointment;
    }
}