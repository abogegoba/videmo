<?php

namespace App\Business\UseCases\Admin;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Criteria\VideoCallHistoryListSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\VideoInterviewCompanyListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\VideoCallHistoryRepository;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList\VideoInterviewCompanyListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList\VideoInterviewCompanyListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList\VideoInterviewCompanyListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList\VideoInterviewCompanyListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList\VideoInterviewCompanyListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList\VideoInterviewCompanyListSearchOutputPort;
use App\Utilities\Log;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class VideoInterviewCompanyListUseCase
 *
 * 企業別ビデオ通話を一覧する
 *
 * @package App\Business\UseCases\Admin
 */
class VideoInterviewCompanyListUseCase implements VideoInterviewCompanyListSearchInteractor, VideoInterviewCompanyListInitializeInteractor
{
    /**
     * １ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var VideoCallHistoryRepository
     */
    private $videoCallHistoryRepository;

    /**
     * VideoInterviewCompanyListUseCase constructor.
     *
     * @param CompanyRepository $companyRepository
     * @param VideoCallHistoryRepository $videoCallHistoryRepository
     */
    public function __construct(
        CompanyRepository $companyRepository,
        VideoCallHistoryRepository $videoCallHistoryRepository
    ) {
        $this->companyRepository = $companyRepository;
        $this->videoCallHistoryRepository = $videoCallHistoryRepository;
    }

    /**
     * 初期化する
     *
     * @param VideoInterviewCompanyListInitializeInputPort $inputPort
     * @param VideoInterviewCompanyListInitializeOutputPort $outputPort
     */
    public function initialize(VideoInterviewCompanyListInitializeInputPort $inputPort, VideoInterviewCompanyListInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // 会社IDから企業を取得
        $company = $this->companyRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->companyId
                ]
            )
        );

        // 会社ID
        $outputPort['companyId'] = $inputPort->companyId;

        // 会社名(会社かな名)を取得
        $companyName = $company->getName();
        $companyNameKana = $company->getNameKana();
        if (!empty($companyNameKana)) {
            $companyName = $companyName . ' (' . $companyNameKana . ')';
        }
        $outputPort['companyName'] = $companyName;

        // ログ出力
        Log::infoOut();
    }

    /**
     * 検索する
     *
     * @param VideoInterviewCompanyListSearchInputPort $inputPort
     * @param VideoInterviewCompanyListSearchOutputPort $outputPort
     */
    public function search(VideoInterviewCompanyListSearchInputPort $inputPort, VideoInterviewCompanyListSearchOutputPort $outputPort): void
    {
        // ログ出力
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
        $videoCallHistories = $this->videoCallHistoryRepository->findByCriteria(
            CriteriaFactory::getInstance()->create(VideoCallHistoryListSearchCriteria::class, VideoInterviewCompanyListSearchExpressionBuilder::class,
                $inputPort,
                [
                    "pager" => $pager
                ]
            )
        );
        $outputPort->videoCallHistories = $videoCallHistories;

        // ログ出力
        Log::infoOut();
    }
}