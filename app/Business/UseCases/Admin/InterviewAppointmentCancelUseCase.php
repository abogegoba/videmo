<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCancel\InterviewAppointmentCancelRevokeInputPort;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCancel\InterviewAppointmentCancelRevokeInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCancel\InterviewAppointmentCancelRevokeOutputPort;
use App\Domain\Entities\InterViewAppointment;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;
use ReLab\Commons\Wrappers\Mail;

/**
 * Class InterviewAppointmentCancelUseCase
 *
 * @package App\Business\UseCases\Admin
 */
class InterviewAppointmentCancelUseCase implements InterviewAppointmentCancelRevokeInteractor
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
     * 取り消す
     *
     * @param InterviewAppointmentCancelRevokeInputPort $inputPort
     * @param InterviewAppointmentCancelRevokeOutputPort $outputPort
     * @throws FatalBusinessException
     */
    public function revoke(InterviewAppointmentCancelRevokeInputPort $inputPort, InterviewAppointmentCancelRevokeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 削除対象の存在確認
        $interviewAppointmentId = $inputPort->interviewAppointmentId;
        try {
            $interviewAppointment = $this->interviewAppointmentRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        "id" => $interviewAppointmentId
                    ],
                    [
                        "forUpdate" => true
                    ]
                )
            );
        } catch (ObjectNotFoundException $e) {
            // キャンセル対象が見つからない場合に例外
            throw new FatalBusinessException("delete_target_not_found");
        }

        // ステータスをキャンセルにする
        $status = $interviewAppointment->getStatus();
        if ($status === InterviewAppointment::STATUS_RESERVATION) {
            $interviewAppointment->setStatus(InterviewAppointment::STATUS_CANCEL);
            $interviewAppointment->setCancelMessage($inputPort->cancelMessage);
            $this->interviewAppointmentRepository->saveOrUpdate($interviewAppointment, true);

            // メール送信
            if ($inputPort->sendMailToMember == 1 || $inputPort->sendMailToCompany == 1) {
                $companyUserAccount = $interviewAppointment->getCompanyUserAccount();
                $memberUserAccount = $interviewAppointment->getMemberUserAccount();

                $title = '【LinkT】面接キャンセルのご案内';
                $dataList['companyAccount'] = $companyUserAccount->getCompanyAccount();
                $dataList['member'] = $memberUserAccount->getMember();
                $dataList['appointmentDatetime'] = $interviewAppointment->getAppointmentDatetime()->format('Y年n月j日 H時i分');
                $dataList['cancelMessage'] = $interviewAppointment->getCancelMessage();
                $data = Data::wrap($dataList);

                // 会員に面接受付メールを送信する
                if ($inputPort->sendMailToMember == 1) {
                    $mail = Mail::getInstance("mail.admin.member.interview_appointment_cancel_mail", $memberUserAccount->getMailAddress(), trans($title), $data);
                    $result = $mail->send();
                    if ($result !== true) {
                        throw new FatalBusinessException("not_send_mail");
                    }
                }

                // 担当者に面接受付メールを送信する
                if ($inputPort->sendMailToCompany == 1) {
                    $mail = Mail::getInstance("mail.admin.client.interview_appointment_cancel_mail", $companyUserAccount->getMailAddress(), trans($title), $data);
                    $result = $mail->send();
                    if ($result !== true) {
                        throw new FatalBusinessException("not_send_mail");
                    }
                }
            }
        }

        //ログ出力
        Log::infoOut();
    }
}