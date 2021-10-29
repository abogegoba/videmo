<?php


namespace App\Business\UseCases\Admin;



use App\Business\Interfaces\Gateways\Criteria\JobApplicationSearchCriteria;
use App\Business\Interfaces\Gateways\Criteria\PrefectureSearchCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\JobApplicationSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Interactors\Admin\JobApplicationList\JobApplicationListInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationList\JobApplicationListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationList\JobApplicationListInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationList\JobApplicationListSearchInputPort;
use App\Business\Interfaces\Interactors\Admin\JobApplicationList\JobApplicationListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationList\JobApplicationListSearchOutputPort;
use App\Domain\Entities\JobApplication;
use App\Utilities\Log;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class JobApplicationListUseCase
 *
 * 求人を一覧する
 *
 * @package App\Business\UseCases\Admin
 */
class JobApplicationListUseCase implements JobApplicationListInitializeInteractor, JobApplicationListSearchInteractor
{
    /**
     *  1ページ最大件数
     */
    const PAGE_LIMIT_COUNT = 50;

    /**
     * @var JobApplicationRepository
     */
    private $jobApplicationRepository;

    /**
     * @var PrefectureRepository
     */
    private $prefectureRepository;

    /**
     * JobApplicationListUseCase constructor.
     *
     * @param JobApplicationRepository $jobApplicationRepository
     * @param PrefectureRepository $prefectureRepository
     */
    public function __construct(jobApplicationRepository $jobApplicationRepository, PrefectureRepository $prefectureRepository)
    {
        $this->jobApplicationRepository = $jobApplicationRepository;
        $this->prefectureRepository = $prefectureRepository;
    }


    public function initialize(JobApplicationListInitializeInputPort $inputPort, JobApplicationListInitializeOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // エリアリスト（都道府県）
        $prefectures = $this->prefectureRepository->findValuesByCriteria(
            CriteriaFactory::getInstance()->create(PrefectureSearchCriteria::class, GeneralExpressionBuilder::class), ["id","name"]
        );
        $prefectureNameList = array_column($prefectures, "name");
        $prefectureIdList = array_column($prefectures, "id");
        $outputPort->prefectureList = array_combine ($prefectureIdList , $prefectureNameList);

        // ステータスリストを取得
        $statusList = JobApplication::STATUS_LIST;
        $outputPort->statusList = $statusList;

        // ログ出力
        Log::infoOut();
    }

    public function search(JobApplicationListSearchInputPort $inputPort, JobApplicationListSearchOutputPort $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        // Inputにページ指定が存在しない場合は新規で作成する
        $pager = $inputPort->pager;
        if(!isset($pager)){
            $pager = new Class() extends Data implements Pager
            {
            };
        }

        // 1ページ最大件数を設定する
        $pager->limit = self::PAGE_LIMIT_COUNT;
        $outputPort->pager = $pager;

        // 検索する
        $criteriaFactory = CriteriaFactory::getInstance();
        $jobApplications = $this->jobApplicationRepository->findByCriteria(
            $criteriaFactory->create(JobApplicationSearchCriteria::class, JobApplicationSearchExpressionBuilder::class,
            $inputPort,
            [
                "pager" => $pager
            ])
        );

        $outputPort->jobApplications = $jobApplications;

        //ログ出力
        Log::infoOut();
    }
}