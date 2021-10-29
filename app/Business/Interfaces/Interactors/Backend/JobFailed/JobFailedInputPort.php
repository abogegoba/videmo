<?php

namespace App\Business\Interfaces\Interactors\Backend\JobFailed;

use App\Business\Interfaces\Interactors\Backend\JobBegin\JobBeginInputPort;

/**
 * Interface JobFailedInputPort
 *
 * @package App\Business\Interfaces\Interactors\Backend\JobFailed
 *
 * @property int $resultStatus 結果ステータス
 * @property array $outputValue 出力値
 */
interface JobFailedInputPort extends JobBeginInputPort
{
    /**
     * 結果ステータス：成功
     *
     * @var int
     */
    const RESULT_STATUS_SUCCESS = 1;

    /**
     * 結果ステータス：エラー
     *
     * @var int
     */
    const RESULT_STATUS_ERROR = 2;
}