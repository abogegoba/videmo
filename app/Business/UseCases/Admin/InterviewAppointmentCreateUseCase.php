<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate\InterviewAppointmentCreateInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate\InterviewAppointmentCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate\InterviewAppointmentCreateInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate\InterviewAppointmentCreateStoreInputPort;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate\InterviewAppointmentCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate\InterviewAppointmentCreateStoreOutputPort;
use App\Domain\Entities\InterViewAppointment;
use App\Utilities\Log;
use Carbon\Carbon;
use ReLab\Commons\Exceptions\BusinessException;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Utilities\UUID;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;

/**
 * Class InterviewAppointmentCreateUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class InterviewAppointmentCreateUseCase implements InterviewAppointmentCreateInitializeInteractor, InterviewAppointmentCreateStoreInteractor
{
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * InterviewAppointmentCreateUseCase constructor.
     *
     * @param CompanyRepository $companyRepository
     * @param MemberRepository $memberRepository
     * @param UserAccountRepository $userAccountRepository
     * @param InterviewAppointmentRepository $InterviewAppointmentRepository
     */
    public function __construct(
        CompanyRepository $companyRepository,
        MemberRepository $memberRepository,
        UserAccountRepository $userAccountRepository,
        InterviewAppointmentRepository $InterviewAppointmentRepository
    )
    {
        $this->companyRepository = $companyRepository;
        $this->memberRepository = $memberRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->interviewAppointmentRepository = $InterviewAppointmentRepository;
    }

    /**
     * 初期化する
     *
     * @param InterviewAppointmentCreateInitializeInputPort $inputPort
     * @param InterviewAppointmentCreateInitializeOutputPort $outputPort
     */
    public function initialize(InterviewAppointmentCreateInitializeInputPort $inputPort, InterviewAppointmentCreateInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 対象企業リスト作成
        $allCompanyList = $this->companyRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class),
            [
                'id',
                'name',
                'nameKana'
            ]
        );
        $companyList = [];
        foreach ($allCompanyList as $company) {
            $companyList[$company['id']]['id'] = $company['id'];
            $companyList[$company['id']]['name'] = $company['name'];
            $companyList[$company['id']]['nameKana'] = $company['nameKana'];
        }
        $outputPort->companyList = $companyList;

        // 対象会員リスト作成
        $allMemberList = $this->memberRepository->findValuesWithUserAccountByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class),
            [
                'id',
                'lastName',
                'firstName',
                'lastNameKana',
                'firstNameKana',
                'phoneNumber',
                'userAccount.mailAddress'
            ]
        );
        $memberList = [];
        foreach ($allMemberList as $member) {
            $memberList[$member['id']]['id'] = $member['id'];
            $memberList[$member['id']]['name'] = $member['lastName'] . ' ' . $member['firstName'];
            $memberList[$member['id']]['nameKana'] = $member['lastNameKana'] . ' ' . $member['firstNameKana'];
            $memberList[$member['id']]['phoneNumber'] = $member['phoneNumber'];
            $memberList[$member['id']]['mailAddress'] = $member['mailAddress'];
        }
        $outputPort->memberList = $memberList;

        //ログ出力
        Log::infoOut();
    }

    /**
     * 登録する
     *
     * @param InterviewAppointmentCreateStoreInputPort $inputPort
     * @param InterviewAppointmentCreateStoreOutputPort $outputPort
     * @throws BusinessException
     * @throws FatalBusinessException
     */
    public function store(InterviewAppointmentCreateStoreInputPort $inputPort, InterviewAppointmentCreateStoreOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 対象企業リスト作成
        $companyUserAccount = null;
        try {
            $companyUserAccount = $this->userAccountRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "companyAccount.company" => $inputPort->companyId
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new BusinessException('not_found_target_company');
        }

        // 対象会員リスト作成
        $memberUserAccount = null;
        try {
            $memberUserAccount = $this->userAccountRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "member" => $inputPort->memberId
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            throw new BusinessException('not_found_target_member');
        }

        $interViewAppointment = new InterViewAppointment();
        $interViewAppointment->setAppointmentDatetime(Carbon::make($inputPort->appointmentDate . ' ' . $inputPort->appointmentTime));
        $interViewAppointment->setContent($inputPort->content);
        $interViewAppointment->setStatus(InterViewAppointment::STATUS_RESERVATION);
        $interViewAppointment->setMemberUserAccount($memberUserAccount);
        $interViewAppointment->setCompanyUserAccount($companyUserAccount);
        $interViewAppointment->setMemberPeerId(UUID::generate(UUID::UUID_RANDOM, UUID::FMT_STRING));
        $interViewAppointment->setCompanyPeerId(UUID::generate(UUID::UUID_RANDOM, UUID::FMT_STRING));

        // 登録する
        $this->interviewAppointmentRepository->saveOrUpdate($interViewAppointment, true);

        // メール送信
        if ($inputPort->sendMailToMember == 1 || $inputPort->sendMailToCompany == 1) {
            $member = $memberUserAccount->getMember();
            $companyAccount = $companyUserAccount->getCompanyAccount();
            $company = $companyAccount->getCompany();
            $frontAppURL = env('FRONT_APP_URL');
            $clientAppURL = env('CLIENT_APP_URL');
            $title = "【LinkT】面接開催日時のご案内";
            $interViewAppointmentId = $interViewAppointment->getId();
            $appointmentDatetime = $interViewAppointment->getAppointmentDatetime();

            $dataList["memberName"] = $member->getLastName() . " " . $member->getFirstName();;
            $dataList["contactURL"] = "$frontAppURL/mypage/contact";
            $dataList["memberVideoInterviewReservationDetailUrl"] = "$frontAppURL/mypage/video/$interViewAppointmentId/reservation-detail";
            $dataList["companyName"] = $company->getName();
            $dataList["pic_name"] = $company->getPicName();
            $dataList["companyAccountUserName"] = $companyAccount->getLastName() . " " . $companyAccount->getFirstName();
            $dataList["companyVideoInterviewReservationDetailUrl"] = "$clientAppURL/mypage/video/$interViewAppointmentId/reservation-detail";
            $dataList["appointmentDatetime"] = $appointmentDatetime->format("Y年m月d日 h:s");
            $data = Data::wrap($dataList);

            // 会員に面接受付メールを送信する
            if ($inputPort->sendMailToMember == 1) {
                $mail = Mail::getInstance("mail.admin.member.interview_appointment_mail", $memberUserAccount->getMailAddress(), trans($title), $data);
                $result = $mail->send();
                if ($result !== true) {
                    throw new FatalBusinessException("not_send_mail");
                }
            }

            // 担当者に面接受付メールを送信する
            if ($inputPort->sendMailToCompany == 1) {
                $mail = Mail::getInstance("mail.admin.client.interview_appointment_mail", $companyUserAccount->getMailAddress(), trans($title), $data);
                $result = $mail->send();
                if ($result !== true) {
                    throw new FatalBusinessException("not_send_mail");
                }
            }
        }

        //ログ出力
        Log::infoOut();
    }
}