<?php

namespace App\Adapters\Http\Middleware;

use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationCheckInputPort;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationCheckOutputPort;
use App\Domain\Model\ClientAuthentication;
use App\Utilities\Log;
use Closure;
use Illuminate\Http\Request;
use ReLab\Commons\Wrappers\Data;

/**
 * Class ClientAuthenticate
 *
 * @package App\Adapters\Http\Middleware
 */
class ClientAuthenticate
{
    /**
     * @var  ClientAuthenticationCheckInteractor
     */
    private $interactor;

    /**
     * ClientAuthenticate constructor.
     *
     * @param ClientAuthenticationCheckInteractor $interactor
     */
    public function __construct(ClientAuthenticationCheckInteractor $interactor)
    {
        $this->interactor = $interactor;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   //ログ出力
        Log::infoIn();

        /** @var ClientAuthentication $clientAuthentication */
        $clientAuthentication = ClientAuthentication::loadSession();

        // セッションに企業会員認証が存在しない場合はログイン画面へリダイレクトする
        if (!isset($clientAuthentication)) {
            //ログ出力
            Log::infoOut();
            return redirect(route("client.login"));
        }

        // INPUT作成
        $inputPort = new class($clientAuthentication) extends Data implements ClientAuthenticationCheckInputPort
        {
        };

        // OUTPUT作成
        $outputPort = new class() extends Data implements ClientAuthenticationCheckOutputPort
        {
        };

        try {
            // 認証を確認する
            $this->interactor->check($inputPort, $outputPort);
            \View::share('clientAuthentication', $outputPort->clientAuthentication);
        } catch (\Exception $e) {
            // ログ出力
            Log::warning($e->getMessage(), ['business_exception' => $e]);

            // 例外が発生した場合はログアウトへリダイレクトする
            return redirect(route("client.logout"));
        }

        //ログ出力
        Log::infoOut();
        return $next($request);
    }
}
