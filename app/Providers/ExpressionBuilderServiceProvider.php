<?php

namespace App\Providers;

use App\Adapters\Gateways\Expression\Builders\AdminMessageDetailSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\AdminMessageListSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\JobApplicationSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\CompanyListSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\InterviewAppointmentListSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\MemberListSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\MemberSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\MessageDetailSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\MessageSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\ModelSentenceListSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\VideoInterviewListSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\VideoInterviewCompanyListSearchDoctrineExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\VideoInterviewMemberListSearchDoctrineExpressionBuilder;
use App\Business\Interfaces\Gateways\Criteria\CompanyListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\AdminMessageDetailSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\AdminMessageListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\InterviewAppointmentListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\JobApplicationSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MemberListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MemberSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MessageDetailSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\MessageSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\ModelSentenceListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\VideoInterviewListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\VideoInterviewCompanyListSearchExpressionBuilder;
use App\Business\Interfaces\Gateways\Expression\Builders\VideoInterviewMemberListSearchExpressionBuilder;
use App\Adapters\Gateways\Expression\Builders\CompanySearchDoctrineExpressionBuilder;
use App\Business\Interfaces\Gateways\Criteria\CompanySearchExpressionBuilder;
use Illuminate\Support\ServiceProvider;
use ReLab\Doctrine\Expression\Builders\GeneralDoctrineExpressionBuilder;

class ExpressionBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 汎用
        $this->app->bind(
            GeneralExpressionBuilder::class,
            GeneralDoctrineExpressionBuilder::class
        );
        // メッセージ
        $this->app->bind(
            MessageSearchExpressionBuilder::class,
            MessageSearchDoctrineExpressionBuilder::class
        );
        // メッセージ詳細
        $this->app->bind(
            MessageDetailSearchExpressionBuilder::class,
            MessageDetailSearchDoctrineExpressionBuilder::class
        );
        // 企業
        $this->app->bind(
            CompanySearchExpressionBuilder::class,
            CompanySearchDoctrineExpressionBuilder::class
        );
        // 企業一覧
        $this->app->bind(
            CompanyListSearchExpressionBuilder::class,
            CompanyListSearchDoctrineExpressionBuilder::class
        );
        // 会員
        $this->app->bind(
            MemberSearchExpressionBuilder::class,
            MemberSearchDoctrineExpressionBuilder::class
        );

        // 求人検索
        $this->app->bind(
            JobApplicationSearchExpressionBuilder::class,
            JobApplicationSearchDoctrineExpressionBuilder::class
        );

        // メッセージ検索
        $this->app->bind(
            AdminMessageListSearchExpressionBuilder::class,
            AdminMessageListSearchDoctrineExpressionBuilder::class
        );

        // 会員一覧
        $this->app->bind(
            MemberListSearchExpressionBuilder::class,
            MemberListSearchDoctrineExpressionBuilder::class
        );

        // 面接予約一覧
        $this->app->bind(
            InterviewAppointmentListSearchExpressionBuilder::class,
            InterviewAppointmentListSearchDoctrineExpressionBuilder::class
        );

        // ビデオ通話履歴一覧
        $this->app->bind(
            VideoInterviewListSearchExpressionBuilder::class,
            VideoInterviewListSearchDoctrineExpressionBuilder::class
        );

        // 企業別ビデオ通話一覧
        $this->app->bind(
            VideoInterviewCompanyListSearchExpressionBuilder::class,
            VideoInterviewCompanyListSearchDoctrineExpressionBuilder::class
        );

        // 会員別ビデオ通話一覧
        $this->app->bind(
            VideoInterviewMemberListSearchExpressionBuilder::class,
            VideoInterviewMemberListSearchDoctrineExpressionBuilder::class
        );

        // 管理側メッセージ詳細
        $this->app->bind(
            AdminMessageDetailSearchExpressionBuilder::class,
            AdminMessageDetailSearchDoctrineExpressionBuilder::class
        );

        // 例文検索
        $this->app->bind(
            ModelSentenceListSearchExpressionBuilder::class,
            ModelSentenceListSearchDoctrineExpressionBuilder::class
        );
    }
}
