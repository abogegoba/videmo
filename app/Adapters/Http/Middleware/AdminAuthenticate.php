<?php

namespace App\Adapters\Http\Middleware;


use App\Adapters\Http\Requests\Request;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckInputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckOutputPort;
use App\Domain\Model\AdminAuthentication;
use App\Utilities\Log;
use Closure;
use ReLab\Commons\Wrappers\Data;

/**
 * Class AdminAuthenticate
 *
 * @package App\Adapters\Http\Middleware
 */
class AdminAuthenticate
{
    /**
     * @var  AdminAuthenticationCheckInteractor
     */
    private $interactor;

    /**
     * AdminAuthenticate constructor.
     *
     * @param AdminAuthenticationCheckInteractor $interactor
     */
    public function __construct(AdminAuthenticationCheckInteractor $interactor)
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

        /** @var AdminAuthentication $adminAuthentication */
        $adminAuthentication = AdminAuthentication::loadSession();

        // セッションに会員認証が存在しない場合はログイン画面へリダイレクトする
        if (!isset($adminAuthentication)) {
            //ログ出力
            Log::infoOut();
            return redirect(route("admin.top"));
        }

        // INPUT作成
        $inputPort = new class($adminAuthentication) extends Data implements AdminAuthenticationCheckInputPort
        {
        };

        // OUTPUT作成
        $outputPort = new class() extends Data implements AdminAuthenticationCheckOutputPort
        {
        };

        try {
            // 認証を確認する
            $this->interactor->check($inputPort, $outputPort);
            \View::share('adminAuthentication', $outputPort->adminAuthentication);
        } catch (\Exception $e) {
            // ログ出力
            Log::warning($e->getMessage(), ['business_exception' => $e]);

            // 例外が発生した場合はログアウトへリダイレクトする
            return redirect(route("admin.logout"));
        }

        //ログ出力
        Log::infoOut();
        return $next($request);
    }
}
