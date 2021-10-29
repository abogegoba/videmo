<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Interactors\Client\Top\TopInitializeInputPort;
use App\Business\Interfaces\Interactors\Client\Top\TopInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\Top\TopInitializeOutputPort;
use App\Utilities\Log;

/**
 * Class TopUseCase
 *
 * @package App\Business\UseCases\Client
 */
class TopUseCase implements TopInitializeInteractor
{
    /**
     * 初期表示
     *
     * @param TopInitializeInputPort $inputPort
     * @param TopInitializeOutputPort $outputPort
     */
    public function initialize(TopInitializeInputPort $inputPort, TopInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        //ログ出力
        Log::infoOut();
    }

}