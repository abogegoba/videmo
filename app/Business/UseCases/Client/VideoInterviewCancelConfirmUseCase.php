<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm\VideoInterviewCancelExecuteInputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm\VideoInterviewCancelExecuteInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm\VideoInterviewCancelConfirmInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm\VideoInterviewCancelConfirmInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm\VideoInterviewCancelConfirmInitializeOutputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm\VideoInterviewCancelExecuteOutputPort;
use App\Domain\Entities\InterViewAppointment;
use App\Domain\Entities\Tag;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;

/**
 * Class VideoInterviewCancelConfirmUseCase
 *
 * @package App\Business\UseCases\Client
 */
class VideoInterviewCancelConfirmUseCase implements VideoInterviewCancelConfirmInitializeInteractor, VideoInterviewCancelExecuteInteractor
{
    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * VideoInterviewReservationDetailUseCase constructor.
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

        // 会員アカウント取得
        $member = $interviewAppointment->getMemberUserAccount()->getMember();

        // 会員名
        $memberLastName = $member->getLastName();
        $memberFirstName = $member->getFirstName();
        $outputPort->memberName = "$memberLastName $memberFirstName";

        // プライベート写真
        $privateImage = $member->getPrivatePhotoFilePathForClientShow();
        $outputPort->privateImage = $privateImage;

        //証明写真
        $idImage = $member->getIdPhotoFilePathForClientShow();
        $outputPort->idImage = $idImage;

        // 学校情報
        $schoolName = $member->getOldSchool()->getName();
        $departmentName = $member->getOldSchool()->getDepartmentName();
        $birthday = $member->getBirthday()->format("Ymd");
        $age = $this->setAge($birthday);
        $graduationPeriod = $member->getOldSchool()->getGraduationPeriod()->format("Y");
        $outputPort->schoolName = $schoolName;
        $outputPort->departmentName = $departmentName;
        $outputPort->age = $age;
        $outputPort->graduationPeriod = $graduationPeriod;

        // ハッシュタグ
        $hashTag = $member->getHashTag();
        if (isset($hashTag)) {
            $hashTagName = $hashTag->getName();
            $hashTagColor = Tag::TAG_COLLAR_CLASS_LIST[$hashTag->getColor()];
            $outputPort->hashTagName = $hashTagName;
            $outputPort->hashTagColor = $hashTagColor;
        }

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

        // 面接キャンセルURL
        $outputPort->videoInterviewCancelUrl = route("client.video-interview.cancel-confirm", ["interviewAppointmentId" => $interviewAppointmentId]);
        // 面接予約詳細URL
        $outputPort->videoInterviewReservationDetailUrl = route("client.video-interview.reservation-detail", ["interviewAppointmentId" => $interviewAppointmentId]);

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
            $template = 'mail.front.client.interview_appointment_cancel_mail';
            $companyUserAccount = $interviewAppointment->getCompanyUserAccount();
            $companyAccount = $companyUserAccount->getCompanyAccount();
            $memberUserAccount = $interviewAppointment->getMemberUserAccount();
            $mailAddress = $memberUserAccount->getMailAddress();
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
        return$interviewAppointment;
    }

    /**
     * 誕生日から現在の年齢取得
     *
     * @param string $birthday
     * @return int
     */
    private function setAge(string $birthday)
    {
        $now = date("Ymd");
        $age = floor($now - $birthday) / 10000;
        return intval($age);
    }
}