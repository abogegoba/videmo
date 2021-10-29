<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\InterviewAppointmentListSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\InterviewAppointmentSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\MemberSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\InterviewAppointmentListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MemberListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList\InterviewAppointmentListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList\InterviewAppointmentListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList\InterviewAppointmentListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList\InterviewAppointmentListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList\InterviewAppointmentListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList\InterviewAppointmentListSearchOutputPort;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListSearchOutputPort;
use App\Domain\Entities\InterViewAppointment;
use App\Utilities\Log;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class MemberListUseCase
 *
 * 会員を一覧する
 *
 * @package App\Business\UseCases\Admin
 */
class InterviewAppointmentListUseCase implements InterviewAppointmentListSearchInteractor, InterviewAppointmentListInitializeInteractor
{
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var InterviewAppointmentRepository
     */
    private $interviewAppointmentRepository;

    /**
     * InterviewAppointmentListUseCase constructor.
     *
     * @param InterviewAppointmentRepository $interviewAppointmentRepository
     */
    public function __construct(
        InterviewAppointmentRepository $interviewAppointmentRepository
    ) {
        $this->interviewAppointmentRepository = $interviewAppointmentRepository;
    }

    /**
     * 初期化する
     *
     * @param InterviewAppointmentListInitializeInputPort $inputPort
     * @param InterviewAppointmentListInitializeOutputPort $outputPort
     */
    public function initialize(InterviewAppointmentListInitializeInputPort $inputPort, InterviewAppointmentListInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // リスト作成
        $outputPort->interviewAppointmentStatusList = InterviewAppointment::STATUS_LIST;

        //ログ出力
        Log::infoOut();
    }

    /**
     * @param InterviewAppointmentListSearchInputPort $inputPort
     * @param InterviewAppointmentListSearchOutputPort $outputPort
     */
    public function search(InterviewAppointmentListSearchInputPort $inputPort, InterviewAppointmentListSearchOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // Inputにページ指定が存在しない場合は新規で作成する
        $pager = $inputPort->pager;
        if (!isset($pager)) {
            $pager = new Class() extends Data implements Pager
            {
            };
        }
        // 1ページ最大件数を設定する
        $pager->limit = self::PAGE_LIMIT_COUNT;
        $outputPort->pager = $pager;

        $interviewAppointments = $this->interviewAppointmentRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(InterviewAppointmentListSearchCriteria::class, InterviewAppointmentListSearchExpressionBuilder::class,
                $inputPort,
                [
                    "pager" => $pager
                ]
            )
        );
        $outputPort->interviewAppointments = $interviewAppointments;

        //ログ出力
        Log::infoOut();
    }
}