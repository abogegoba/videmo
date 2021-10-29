<?php

namespace App\Adapters\Http\Middleware;

use App\Utilities\Log;
use Illuminate\Http\Request;
use Closure;

/**
 * Class Transaction
 *
 * @package App\Http\Middleware
 */
class Transaction
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next)
    {
        //ログ出力
        Log::infoIn();

        // トランザクション開始
        $transaction = \ReLab\Commons\Wrappers\Transaction::getInstance();
        $transaction->start();

        $response = $next($request);

        // トランザクション終了
        $transaction->commit();

        //ログ出力
        Log::infoOut();

        return $response;
    }
}
