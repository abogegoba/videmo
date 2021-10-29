<?php

namespace App\Business\UseCases\Backend;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\JobHistoryRepository;
use App\Business\Interfaces\Interactors\Backend\JobBegin\JobBeginInputPort;
use App\Business\Interfaces\Interactors\Backend\JobBegin\JobBeginInteractor;
use App\Business\Interfaces\Interactors\Backend\JobFailed\JobFailedInputPort;
use App\Business\Interfaces\Interactors\Backend\JobFailed\JobFailedInteractor;
use App\Business\Interfaces\Interactors\Backend\JobFinish\JobFinishInputPort;
use App\Business\Interfaces\Interactors\Backend\JobFinish\JobFinishInteractor;
use App\Business\Interfaces\Interactors\Backend\JobShow\JobShowInputPort;
use App\Business\Interfaces\Interactors\Backend\JobShow\JobShowInteractor;
use App\Business\Interfaces\Interactors\Backend\JobShow\JobShowOutputPort;
use App\Business\Interfaces\Interactors\Backend\JobStore\JobStoreInputPort;
use App\Business\Interfaces\Interactors\Backend\JobStore\JobStoreInteractor;
use App\Business\Interfaces\Interactors\Backend\JobStore\JobStoreOutputPort;
use App\Domain\Entities\JobHistory;
use App\Utilities\Log;
use ReLab\Commons\Wrappers\CriteriaFactory;
use ReLab\Commons\Wrappers\Data;

/**
 * Class JobHistoryUseCase
 *
 * @package App\Business\UseCases\Backend
 */
class JobHistoryUseCase implements JobStoreInteractor, JobBeginInteractor, JobFinishInteractor, JobFailedInteractor, JobShowInteractor
{
    /**
     * @var JobHistoryRepository
     */
    private $jobHistoryRepository;

    /**
     * JobHistoryUseCase constructor.
     *
     * @param JobHistoryRepository $jobHistoryRepository
     */
    public function __construct(JobHistoryRepository $jobHistoryRepository)
    {
        // ログ出力
        Log::infoIn();

        $this->jobHistoryRepository = $jobHistoryRepository;

        // ログ出力
        Log::infoOut();
    }

    /**
     * ジョブを登録する
     *
     * @param JobStoreInputPort $inputPort
     * @param JobStoreOutputPort $outputPort
     */
    public function store($inputPort, $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $this->_store($inputPort, $outputPort);

        // ログ出力
        Log::infoOut();
    }

    /**
     * ジョブを開始する
     *
     * @param JobBeginInputPort $inputPort
     */
    public function begin($inputPort): void
    {
        // ログ出力
        Log::infoIn();

        $this->_begin($inputPort);

        // ログ出力
        Log::infoOut();
    }

    /**
     * ジョブを失敗する
     *
     * @param JobFailedInputPort $inputPort
     */
    public function failed($inputPort): void
    {
        // ログ出力
        Log::infoIn();

        $this->_failed($inputPort);

        // ログ出力
        Log::infoOut();
    }

    /**
     * ジョブを終了する
     *
     * @param JobFinishInputPort $inputPort
     */
    public function finish($inputPort): void
    {
        // ログ出力
        Log::infoIn();

        $this->_finish($inputPort);

        // ログ出力
        Log::infoOut();
    }

    /**
     * ジョブを参照する
     *
     * @param JobShowInputPort $inputPort
     * @param JobShowOutputPort $outputPort
     */
    public function show($inputPort, $outputPort): void
    {
        // ログ出力
        Log::infoIn();

        $this->_show($inputPort, $outputPort);

        // ログ出力
        Log::infoOut();
    }

    /**
     *  ジョブを登録する（オーバーロード）
     *
     * @param JobStoreInputPort $inputPort
     * @param JobStoreOutputPort $outputPort
     */
    private function _store(JobStoreInputPort $inputPort, JobStoreOutputPort $outputPort): void
    {
        $jobHistory = JobHistory::create($inputPort->jobId, $inputPort->inputValue);
        $this->jobHistoryRepository->saveOrUpdate($jobHistory, true);
        $outputPort->jobHistoryId = $jobHistory->getId();
    }

    /**
     * ジョブを開始する（オーバーロード）
     *
     * @param JobBeginInputPort $inputPort
     */
    private function _begin(JobBeginInputPort $inputPort): void
    {
        $jobHistory = $this->jobHistoryRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->jobHistoryId
                ]
            )
        );
        $this->jobHistoryRepository->saveOrUpdate($jobHistory->changeProcess(), true);
    }

    /**
     * ジョブを失敗する（オーバーロード）
     *
     * @param JobFailedInputPort $inputPort
     */
    private function _failed(JobFailedInputPort $inputPort): void
    {
        $jobHistory = $this->jobHistoryRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->jobHistoryId
                ]
            )
        );
        /** @var Data $outputValue */
        $outputValue = $inputPort->outputValue;
        if (isset($outputValue)) {
            $outputValue = $outputValue->toArray();
        }
        $this->jobHistoryRepository->saveOrUpdate($jobHistory->changeFailed($outputValue), true);
    }

    /**
     * ジョブを終了する（オーバーロード）
     *
     * @param JobFinishInputPort $inputPort
     */
    private function _finish(JobFinishInputPort $inputPort): void
    {
        $jobHistory = $this->jobHistoryRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->jobHistoryId
                ]
            )
        );
        /** @var Data $outputValue */
        $outputValue = $inputPort->outputValue;
        if (isset($outputValue)) {
            $outputValue = $outputValue->toArray();
        }
        if ($inputPort->resultStatus == JobFinishInputPort::RESULT_STATUS_ERROR) {
            $jobHistory->changError($outputValue);
        } else {
            $jobHistory->changeSuccess($outputValue);
        }
        $this->jobHistoryRepository->saveOrUpdate($jobHistory, true);
    }

    /**
     * ジョブを確認する
     *
     * @param JobShowInputPort $inputPort
     * @param JobShowOutputPort $outputValue
     */
    private function _show(JobShowInputPort $inputPort, JobShowOutputPort $outputValue): void
    {
        $outputValue->jobHistory = $this->jobHistoryRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->jobHistoryId,
                    "jobId" => $inputPort->jobId
                ]
            )
        );
    }
}