<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Client\VideoInterviewReservationDetail\VideoInterviewReservationDetailInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\VideoInterviewReservationDetail\VideoInterviewReservationDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewReservationDetail\VideoInterviewReservationDetailInitializeOutputPort;
use App\Business\Services\UseLoggedInCompanyAccountTrait;
use App\Domain\Entities\InterViewAppointment;
use App\Domain\Entities\Tag;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class VideoInterviewReservationDetailUseCase
 *
 * @package App\Business\UseCases\Client
 */
class VideoInterviewReservationDetailUseCase implements VideoInterviewReservationDetailInitializeInteractor
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

        // 面接URL
        $outputPort->videoInterviewRoomUrl = route("client.video-interview.room", ["interviewAppointmentId" => $interviewAppointmentId]);

        // 面接キャンセルURL
        $outputPort->videoInterviewCancelUrl = route("client.video-interview.cancel-confirm", ["userAccountId" => $interviewAppointmentId]);

        // 内容
        $content = $interviewAppointment->getContent();
        $outputPort->content = $content;

        //ログ出力
        Log::infoOut();
    }

    /**
     * 誕生日から現在の年齢取得
     *
     * @param string $birthday
     * @return int
     */
    private function setAge(string $birthday) {
        $now = date("Ymd");
        $age = floor($now-$birthday)/10000;
        return intval($age);
    }
}