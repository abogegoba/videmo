<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\MemberSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\MemberListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListSearchOutputPort;
use App\Domain\Entities\School;
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
class MemberListUseCase implements MemberListSearchInteractor, MemberListInitializeInteractor
{
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * MemberListUseCase constructor.
     *
     * @param MemberRepository $memberRepository
     */
    public function __construct(
        MemberRepository $memberRepository
    ) {
        $this->memberRepository = $memberRepository;
    }

    /**
     * 初期化する
     *
     * @param MemberListInitializeInputPort $inputPort
     * @param MemberListInitializeOutputPort $outputPort
     */
    public function initialize(MemberListInitializeInputPort $inputPort, MemberListInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 卒業年月リスト
        $outputPort->yearList = School::getGraduationTwelveYearListYearAgo();
        $outputPort->monthList = School::getAllMonthList();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 検索する
     *
     * @param MemberListSearchInputPort $inputPort
     * @param MemberListSearchOutputPort $outputPort
     */
    public function search(MemberListSearchInputPort $inputPort, MemberListSearchOutputPort $outputPort): void
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

            $members = $this->memberRepository->findByCriteria(
                CriteriaFactory::getInstance()->create(MemberSearchCriteria::class, MemberListSearchExpressionBuilder::class,
                    $inputPort,
                    [
                        "pager" => $pager
                    ]
                )
            );
        $outputPort->members = $members;

        //ログ出力
        Log::infoOut();
    }
}