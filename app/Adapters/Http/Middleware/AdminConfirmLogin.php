<?php

namespace App\Adapters\Http\Middleware;

use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckInputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckOutputPort;
use App\Domain\Model\AdminAuthentication;
use App\Utilities\Log;
use Closure;
use Illuminate\Http\Request;
use ReLab\Commons\Wrappers\Data;

/**
 * Class AdminConfirmLogin
 *
 * @package App\Adapters\Http\Middleware
 */
class AdminConfirmLogin
{
    /**
     * @var  AdminAuthenticationCheckInteractor
     */
    private $interactor;

    /**
     * FrontAuthenticate constructor.
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
    {
        //ログ出力
        Log::infoIn();

        /** @var AdminAuthentication $memberAuthentication */
        $adminAuthentication = AdminAuthentication::loadSession();

        // OUTPUT作成
        $outputPort = new class() extends Data implements AdminAuthenticationCheckOutputPort
        {
        };

        // セッションに会員認証が存在する場合
        if (isset($adminAuthentication)) {
            // INPUT作成
            $inputPort = new class($adminAuthentication) extends Data implements AdminAuthenticationCheckInputPort
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
        } else {
            // セッションに会員認証が存在しない場合はnullを返す。
            $outputPort->adminAuthentication = null;
        }

        //ログ出力
        Log::infoOut();

        return $next($request);
    }
}
