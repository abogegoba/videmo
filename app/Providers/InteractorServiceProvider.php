<?php

namespace App\Providers;

use App\Business\Interfaces\Interactors\Admin\CompanyCreate\CompanyCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyCreate\CompanyCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCancel\InterviewAppointmentCancelRevokeInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate\InterviewAppointmentCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentDetail\InterviewAppointmentDetailShowInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationDelete\JobApplicationDeleteInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationList\JobApplicationListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationList\JobApplicationListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationLoginInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationLogoutInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminLogout\AdminLogoutInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyEdit\CompanyEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyEdit\CompanyEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyList\CompanyListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\CompanyList\CompanyListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberEdit\MemberEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\MessageDetail\AdminMessageDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MessageDetail\AdminMessageStatusUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\MessageList\AdminMessageListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MessageList\AdminMessageListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentCreate\InterviewAppointmentCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList\InterviewAppointmentListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\InterviewAppointmentList\InterviewAppointmentListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberDelete\MemberDeleteInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberCreate\MemberCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberEdit\MemberEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\MemberList\MemberListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationCreate\JobApplicationCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationCreate\JobApplicationCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationEdit\JobApplicationEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationEdit\JobApplicationEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\JobApplicationPreview\JobApplicationPreviewInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate\ModelSentenceCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceCreate\ModelSentenceCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceDelete\ModelSentenceDeleteInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit\ModelSentenceEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceEdit\ModelSentenceEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceList\ModelSentenceListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\ModelSentenceList\ModelSentenceListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewList\VideoInterviewListInitializeInteractor as AdminVideoInterviewListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewList\VideoInterviewListSearchInteractor as AdminVideoInterviewListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList\VideoInterviewCompanyListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewCompanyList\VideoInterviewCompanyListSearchInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList\VideoInterviewMemberListInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\VideoInterviewMemberList\VideoInterviewMemberListSearchInteractor;
use App\Business\Interfaces\Interactors\Backend\JobBegin\JobBeginInteractor;
use App\Business\Interfaces\Interactors\Backend\JobFailed\JobFailedInteractor;
use App\Business\Interfaces\Interactors\Backend\JobFinish\JobFinishInteractor;
use App\Business\Interfaces\Interactors\Backend\JobShow\JobShowInteractor;
use App\Business\Interfaces\Interactors\Backend\JobStore\JobStoreInteractor;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationLoginInteractor;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationLogoutInteractor;
use App\Business\Interfaces\Interactors\Client\ClientLogout\ClientLogoutInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit\CompanyBasicInformationEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit\CompanyBasicInformationEditPreviewInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyBasicInformationEdit\CompanyBasicInformationEditStoreInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingCreate\CompanyRecruitingCreateStoreInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingDelete\CompanyRecruitingDeleteInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingDelete\CompanyRecruitingDeleteInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit\CompanyRecruitingEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingEdit\CompanyRecruitingEditStoreInteractor;
use App\Business\Interfaces\Interactors\Client\CompanyRecruitingList\CompanyRecruitingListInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\MemberCsvImport\MemberCsvImportInteractor;
use App\Business\Interfaces\Interactors\Client\StudentSearch\StudentSearchInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\StudentSearch\StudentSearchInteractor;
use App\Business\Interfaces\Interactors\Client\StudentDetail\StudentDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomEndInteractor;
use App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomStartInteractor;
use App\Business\Interfaces\Interactors\Front\CompanyDetail\CompanyDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\CompanySearch\CompanySearchInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\CompanySearch\CompanySearchInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactCompleteInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactConfirmInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactExecuteInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Contact\ContactToConfirmInteractor;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationLoginInteractor;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationLogoutInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryCompleteInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryConfirmInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeFiveInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeFourInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeOneInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeThreeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryInitializeTwoInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryReceptionInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryStoreInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToConfirmInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageFourInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageOneInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageThreeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberEntry\MemberEntryToNextPageTwoInteractor;
use App\Business\Interfaces\Interactors\Front\MemberLogout\MemberLogoutInteractor;
use App\Business\Interfaces\Interactors\Front\MessageDetail\MessageDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MessageDetail\MessageDetailSendInteractor;
use App\Business\Interfaces\Interactors\Front\MessageList\MessageListInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Profile\ProfileInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Profile\ProfilePreviewInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileAddressEdit\ProfileAddressEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileAddressEdit\ProfileAddressEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileCareerEdit\ProfileCareerEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileCareerEdit\ProfileCareerEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit\ProfileDesiredEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileDesiredEdit\ProfileDesiredEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit\ProfileIdAndPassEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileIdAndPassEdit\ProfileIdAndPassEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit\ProfileLanguageEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileLanguageEdit\ProfileLanguageEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit\ProfilePersonalEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePersonalEdit\ProfilePersonalEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit\ProfilePhotoEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePhotoEdit\ProfilePhotoEditUpdateInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePREdit\ProfilePREditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfilePREdit\ProfilePREditUpdateInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit\ProfileSchoolEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileSchoolEdit\ProfileSchoolEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit\ProfileSelfIntroductionEditInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\ProfileSelfIntroductionEdit\ProfileSelfIntroductionEditStoreInteractor;
use App\Business\Interfaces\Interactors\Front\Top\TopInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\Top\TopSearchInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm\VideoInterviewCancelExecuteInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewCancelConfirm\VideoInterviewCancelConfirmInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewList\VideoInterviewListInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewReservationDetail\VideoInterviewReservationDetailInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\VideoInterviewRoom\VideoInterviewRoomInitializeInteractor;
use App\Business\UseCases\Admin\AdminMessageDetailUseCase;
use App\Business\UseCases\Admin\AdminMessageListUseCase;
use App\Business\UseCases\Admin\CompanyCreateUseCase;
use App\Business\UseCases\Admin\InterviewAppointmentDetailUseCase;
use App\Business\UseCases\Admin\JobApplicationDeleteUseCase;
use App\Business\UseCases\Admin\JobApplicationListUseCase;
use App\Business\UseCases\Admin\AdminLogoutUseCase;
use App\Business\UseCases\Admin\CompanyEditUseCase;
use App\Business\UseCases\Admin\CompanyListUseCase;
use App\Business\UseCases\Admin\InterviewAppointmentCancelUseCase;
use App\Business\UseCases\Admin\InterviewAppointmentCreateUseCase;
use App\Business\UseCases\Admin\InterviewAppointmentListUseCase;
use App\Business\UseCases\Admin\MemberDeleteUseCase;
use App\Business\UseCases\Admin\MemberCreateUseCase;
use App\Business\UseCases\Admin\MemberEditUseCase;
use App\Business\UseCases\Admin\MemberListUseCase;
use App\Business\UseCases\Admin\JobApplicationCreateUseCase;
use App\Business\UseCases\Admin\JobApplicationEditUseCase;
use App\Business\UseCases\Admin\JobApplicationPreviewUseCase;
use App\Business\UseCases\Admin\ModelSentenceCreateUseCase;
use App\Business\UseCases\Admin\ModelSentenceDeleteUseCase;
use App\Business\UseCases\Admin\ModelSentenceEditUseCase;
use App\Business\UseCases\Admin\ModelSentenceListUseCase;
use App\Business\UseCases\Admin\VideoInterviewListUseCase as AdminVideoInterviewListUseCase;
use App\Business\UseCases\Admin\VideoInterviewCompanyListUseCase;
use App\Business\UseCases\Admin\VideoInterviewMemberListUseCase;
use App\Business\UseCases\Backend\JobHistoryUseCase;
use App\Business\UseCases\Client\ClientAuthenticationUseCase;
use App\Business\UseCases\Client\ClientLogoutUseCase;
use App\Business\UseCases\Client\CompanyRecruitingCreateUseCase;
use App\Business\UseCases\Client\CompanyRecruitingDeleteUseCase;
use App\Business\UseCases\Client\CompanyRecruitingEditUseCase;
use App\Business\UseCases\Client\CompanyRecruitingListUseCase;
use App\Business\UseCases\Client\MemberCsvImportUseCase;;
use App\Business\UseCases\Client\StudentDetailUseCase;
use App\Business\UseCases\Client\StudentSearchUseCase;
use App\Business\UseCases\Client\CompanyBasicInformationEditUseCase;
use App\Business\UseCases\Front\AdminAuthenticationUseCase;
use App\Business\UseCases\Front\CompanyDetailUseCase;
use App\Business\UseCases\Front\CompanySearchUseCase;
use App\Business\UseCases\Front\ContactUseCase;
use App\Business\UseCases\Front\MemberAuthenticationUseCase;
use App\Business\UseCases\Front\MemberEntryUseCase;
use App\Business\UseCases\Front\MemberLogoutUseCase;
use App\Business\UseCases\Front\MessageDetailUseCase;
use App\Business\UseCases\Front\MessageListUseCase;
use App\Business\UseCases\Front\ProfileAddressEditUseCase;
use App\Business\UseCases\Front\ProfileCareerEditUseCase;
use App\Business\UseCases\Front\ProfileDesiredEditUseCase;
use App\Business\UseCases\Front\ProfileIdAndPassEditUseCase;
use App\Business\UseCases\Front\ProfileLanguageEditUseCase;
use App\Business\UseCases\Front\ProfilePersonalEditUseCase;
use App\Business\UseCases\Front\ProfilePhotoEditUseCase;
use App\Business\UseCases\Front\ProfilePREditUseCase;
use App\Business\UseCases\Front\ProfileSchoolEditUseCase;
use App\Business\UseCases\Front\ProfileSelfIntroductionEditUseCase;
use App\Business\UseCases\Front\ProfileUseCase;
use App\Business\UseCases\Front\TopUseCase;
use App\Business\UseCases\Front\VideoInterviewCancelConfirmUseCase;
use App\Business\UseCases\Front\VideoInterviewListUseCase;
use App\Business\UseCases\Front\VideoInterviewReservationDetailUseCase;
use App\Business\UseCases\Front\VideoInterviewRoomUseCase;
use Illuminate\Support\ServiceProvider;

class InteractorServiceProvider extends ServiceProvider
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
        /********************************************************************
         * Front
         ********************************************************************/
        // トップを表示する
        $this->app->singleton(
            TopInitializeInteractor::class,
            TopUseCase::class
        );
        $this->app->singleton(
            TopSearchInteractor::class,
            TopUseCase::class
        );

        // 会員を認証する
        $this->app->singleton(
            MemberAuthenticationInitializeInteractor::class,
            MemberAuthenticationUseCase::class
        );
        $this->app->singleton(
            MemberAuthenticationLoginInteractor::class,
            MemberAuthenticationUseCase::class
        );
        $this->app->singleton(
            MemberAuthenticationCheckInteractor::class,
            MemberAuthenticationUseCase::class
        );
        $this->app->singleton(
            MemberAuthenticationLogoutInteractor::class,
            MemberAuthenticationUseCase::class
        );

        // 企業を検索する
        $this->app->singleton(
            CompanySearchInitializeInteractor::class,
            CompanySearchUseCase::class
        );
        $this->app->singleton(
            CompanySearchInteractor::class,
            CompanySearchUseCase::class
        );

        // 企業を参照する
        $this->app->singleton(
            CompanyDetailInitializeInteractor::class,
            CompanyDetailUseCase::class
        );

        // 会員を登録する(基本情報入力）
        $this->app->singleton(
            MemberEntryInitializeOneInteractor::class,
            MemberEntryUseCase::class
        );
        $this->app->singleton(
            MemberEntryToNextPageOneInteractor::class,
            MemberEntryUseCase::class
        );

        // 会員を登録する(現住所・連絡先入力）
        $this->app->singleton(
            MemberEntryInitializeTwoInteractor::class,
            MemberEntryUseCase::class
        );

        $this->app->singleton(
            MemberEntryToNextPageTwoInteractor::class,
            MemberEntryUseCase::class
        );

        // 会員を登録する(学校情報入力）
        $this->app->singleton(
            MemberEntryInitializeThreeInteractor::class,
            MemberEntryUseCase::class
        );

        $this->app->singleton(
            MemberEntryToNextPageThreeInteractor::class,
            MemberEntryUseCase::class
        );

        // 会員を登録する(志望情報入力）
        $this->app->singleton(
            MemberEntryInitializeFourInteractor::class,
            MemberEntryUseCase::class
        );

        $this->app->singleton(
            MemberEntryToNextPageFourInteractor::class,
            MemberEntryUseCase::class
        );

        // 会員を登録する(ID・パスワード入力）
        $this->app->singleton(
            MemberEntryInitializeFiveInteractor::class,
            MemberEntryUseCase::class
        );

        $this->app->singleton(
            MemberEntryToConfirmInteractor::class,
            MemberEntryUseCase::class
        );

        // 会員を登録する（確認）
        $this->app->singleton(
            MemberEntryConfirmInitializeInteractor::class,
            MemberEntryUseCase::class
        );
        $this->app->singleton(
            MemberEntryStoreInteractor::class,
            MemberEntryUseCase::class
        );

        // 会員を登録する（受付）
        $this->app->singleton(
            MemberEntryReceptionInitializeInteractor::class,
            MemberEntryUseCase::class
        );

        // 会員を登録する（完了）
        $this->app->singleton(
            MemberEntryCompleteInitializeInteractor::class,
            MemberEntryUseCase::class
        );

        // ログアウトする
        $this->app->singleton(
            MemberLogoutInteractor::class,
            MemberLogoutUseCase::class
        );

        // メッセージを一覧する
        $this->app->singleton(
            MessageListInitializeInteractor::class,
            MessageListUseCase::class
        );

        // メッセージを送信する
        $this->app->singleton(
            MessageDetailInitializeInteractor::class,
            MessageDetailUseCase::class
        );
        $this->app->singleton(
            MessageDetailSendInteractor::class,
            MessageDetailUseCase::class
        );

        // ビデオ面接企業一覧（ビデオ面接予約を一覧する・ビデオ面接履歴を一覧する）
        $this->app->singleton(
            VideoInterviewListInitializeInteractor::class,
            VideoInterviewListUseCase::class
        );

        // ビデオ面接予約詳細を表示する
        $this->app->singleton(
            VideoInterviewReservationDetailInitializeInteractor::class,
            VideoInterviewReservationDetailUseCase::class
        );

        // ビデオ面接予約をキャンセルする
        $this->app->singleton(
            VideoInterviewCancelConfirmInitializeInteractor::class,
            VideoInterviewCancelConfirmUseCase::class
        );
        $this->app->singleton(
            VideoInterviewCancelExecuteInteractor::class,
            VideoInterviewCancelConfirmUseCase::class
        );

        // ビデオ面接に参加する
        $this->app->singleton(
            VideoInterviewRoomInitializeInteractor::class,
            VideoInterviewRoomUseCase::class
        );

        // ビデオ面接を開始する
        $this->app->singleton(
            VideoInterviewRoomStartInteractor::class,
            VideoInterviewRoomUseCase::class
        );

        // ビデオ面接を終了する
        $this->app->singleton(
            VideoInterviewRoomEndInteractor::class,
            VideoInterviewRoomUseCase::class
        );

        // プロフィールを表示する
        $this->app->singleton(
            ProfileInitializeInteractor::class,
            ProfileUseCase::class
        );

        $this->app->singleton(
            ProfilePreviewInteractor::class,
            ProfileUseCase::class
        );

        // プロフィールを変更する（個人情報）
        $this->app->singleton(
            ProfilePersonalEditInitializeInteractor::class,
            ProfilePersonalEditUseCase::class
        );
        $this->app->singleton(
            ProfilePersonalEditStoreInteractor::class,
            ProfilePersonalEditUseCase::class
        );

        // プロフィールを変更する（現在住所・連絡先）
        $this->app->singleton(
            ProfileAddressEditInitializeInteractor::class,
            ProfileAddressEditUseCase::class
        );
        $this->app->singleton(
            ProfileAddressEditStoreInteractor::class,
            ProfileAddressEditUseCase::class
        );

        // プロフィールを変更する（学校情報）
        $this->app->singleton(
            ProfileSchoolEditInitializeInteractor::class,
            ProfileSchoolEditUseCase::class
        );
        $this->app->singleton(
            ProfileSchoolEditStoreInteractor::class,
            ProfileSchoolEditUseCase::class
        );

        // プロフィールを変更する（写真・ハッシュタグ）
        $this->app->singleton(
            ProfilePhotoEditInitializeInteractor::class,
            ProfilePhotoEditUseCase::class
        );
        $this->app->singleton(
            ProfilePhotoEditUpdateInteractor::class,
            ProfilePhotoEditUseCase::class
        );

        // プロフィールを変更する（PR）
        $this->app->singleton(
            ProfilePREditInitializeInteractor::class,
            ProfilePREditUseCase::class
        );
        $this->app->singleton(
            ProfilePREditUpdateInteractor::class,
            ProfilePREditUseCase::class
        );

        // プロフィールを変更する（自己紹介）
        $this->app->singleton(
            ProfileSelfIntroductionEditInitializeInteractor::class,
            ProfileSelfIntroductionEditUseCase::class
        );
        $this->app->singleton(
            ProfileSelfIntroductionEditStoreInteractor::class,
            ProfileSelfIntroductionEditUseCase::class
        );

        // プロフィールを変更する（志望情報）
        $this->app->singleton(
            ProfileDesiredEditInitializeInteractor::class,
            ProfileDesiredEditUseCase::class
        );
        $this->app->singleton(
            ProfileDesiredEditStoreInteractor::class,
            ProfileDesiredEditUseCase::class
        );

        // プロフィールを変更する（語学・資格）
        $this->app->singleton(
            ProfileLanguageEditInitializeInteractor::class,
            ProfileLanguageEditUseCase::class
        );
        $this->app->singleton(
            ProfileLanguageEditStoreInteractor::class,
            ProfileLanguageEditUseCase::class
        );

        // プロフィールを変更する（経歴）
        $this->app->singleton(
            ProfileCareerEditInitializeInteractor::class,
            ProfileCareerEditUseCase::class
        );
        $this->app->singleton(
            ProfileCareerEditStoreInteractor::class,
            ProfileCareerEditUseCase::class
        );

        // プロフィールを変更する（ID・パスワード）
        $this->app->singleton(
            ProfileIdAndPassEditInitializeInteractor::class,
            ProfileIdAndPassEditUseCase::class
        );
        $this->app->singleton(
            ProfileIdAndPassEditStoreInteractor::class,
            ProfileIdAndPassEditUseCase::class
        );

        // お問合せ（入力）
        $this->app->singleton(
            ContactInitializeInteractor::class,
            ContactUseCase::class
        );

        // お問合せ（確認画面へ遷移）
        $this->app->singleton(
            ContactToConfirmInteractor::class,
            ContactUseCase::class
        );

        // お問合せ（確認）
        $this->app->singleton(
            ContactConfirmInitializeInteractor::class,
            ContactUseCase::class
        );

        // お問合せ（実行）
        $this->app->singleton(
            ContactExecuteInteractor::class,
            ContactUseCase::class
        );

        // お問合せ（完了）
        $this->app->singleton(
            ContactCompleteInitializeInteractor::class,
            ContactUseCase::class
        );
        /********************************************************************
         * Client
         ********************************************************************/
        // トップを表示する
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\Top\TopInitializeInteractor::class,
            \App\Business\UseCases\Client\TopUseCase::class
        );

        // 企業会員を認証する
        $this->app->singleton(
            ClientAuthenticationInitializeInteractor::class,
            ClientAuthenticationUseCase::class
        );
        $this->app->singleton(
            ClientAuthenticationLoginInteractor::class,
            ClientAuthenticationUseCase::class
        );
        $this->app->singleton(
            ClientAuthenticationCheckInteractor::class,
            ClientAuthenticationUseCase::class
        );
        $this->app->singleton(
            ClientAuthenticationLogoutInteractor::class,
            ClientAuthenticationUseCase::class
        );

        // 学生を検索する
        $this->app->singleton(
            StudentSearchInitializeInteractor::class,
            StudentSearchUseCase::class
        );
        $this->app->singleton(
            StudentSearchInteractor::class,
            StudentSearchUseCase::class
        );

        // 学生を参照する
        $this->app->singleton(
            StudentDetailInitializeInteractor::class,
            StudentDetailUseCase::class
        );

        // ログアウトする
        $this->app->singleton(
            ClientLogoutInteractor::class,
            ClientLogoutUseCase::class
        );

        // メッセージを一覧する
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\MessageList\MessageListInitializeInteractor::class,
            \App\Business\UseCases\Client\MessageListUseCase::class
        );

        // メッセージを送信する
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\MessageDetail\MessageDetailInitializeInteractor::class,
            \App\Business\UseCases\Client\MessageDetailUseCase::class
        );
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\MessageDetail\MessageDetailSendInteractor::class,
            \App\Business\UseCases\Client\MessageDetailUseCase::class
        );

        // ビデオ面接企業一覧（ビデオ面接予約を一覧する・ビデオ面接履歴を一覧する）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewList\VideoInterviewListInitializeInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewListUseCase::class
        );

        // ビデオ面接予約を登録する（入力）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewEntry\VideoInterviewEntryInitializeInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewEntryUseCase::class
        );

        // ビデオ面接予約を登録する（確認画面へ遷移）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewEntry\VideoInterviewEntryToConfirmInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewEntryUseCase::class
        );

        // ビデオ面接予約を登録する（確認）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewEntry\VideoInterviewEntryConfirmInitializeInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewEntryUseCase::class
        );

        // ビデオ面接予約を登録する（登録）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewEntry\VideoInterviewEntryExecuteInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewEntryUseCase::class
        );

        // ビデオ面接予約詳細を表示する
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewReservationDetail\VideoInterviewReservationDetailInitializeInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewReservationDetailUseCase::class
        );

        // ビデオ面接予約をキャンセルする
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm\VideoInterviewCancelConfirmInitializeInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewCancelConfirmUseCase::class
        );
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewCancelConfirm\VideoInterviewCancelExecuteInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewCancelConfirmUseCase::class
        );

        // ビデオ面接に参加する
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\VideoInterviewRoom\VideoInterviewRoomInitializeInteractor::class,
            \App\Business\UseCases\Client\VideoInterviewRoomUseCase::class
        );

        // 企業情報を変更する
        $this->app->singleton(
            CompanyBasicInformationEditInitializeInteractor::class,
            CompanyBasicInformationEditUseCase::class
        );

        $this->app->singleton(
            CompanyBasicInformationEditStoreInteractor::class,
            CompanyBasicInformationEditUseCase::class
        );

        $this->app->singleton(
            CompanyBasicInformationEditPreviewInteractor::class,
            CompanyBasicInformationEditUseCase::class
        );

        // 求人を一覧する
        $this->app->singleton(
            CompanyRecruitingListInitializeInteractor::class,
            CompanyRecruitingListUseCase::class
        );

        // 求人を登録する
        $this->app->singleton(
            CompanyRecruitingCreateInitializeInteractor::class,
            CompanyRecruitingCreateUseCase::class
        );
        $this->app->singleton(
            CompanyRecruitingCreateStoreInteractor::class,
            CompanyRecruitingCreateUseCase::class
        );

        // 求人を変更する
        $this->app->singleton(
            CompanyRecruitingEditInitializeInteractor::class,
            CompanyRecruitingEditUseCase::class
        );
        $this->app->singleton(
            CompanyRecruitingEditStoreInteractor::class,
            CompanyRecruitingEditUseCase::class
        );

        // 求人を削除する
        $this->app->singleton(
            CompanyRecruitingDeleteInitializeInteractor::class,
            CompanyRecruitingDeleteUseCase::class
        );
        $this->app->singleton(
            CompanyRecruitingDeleteInteractor::class,
            CompanyRecruitingDeleteUseCase::class
        );

        // お問合せ（入力）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\Contact\ContactInitializeInteractor::class,
            \App\Business\UseCases\Client\ContactUseCase::class
        );

        // お問合せ（確認画面へ遷移）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\Contact\ContactToConfirmInteractor::class,
            \App\Business\UseCases\Client\ContactUseCase::class
        );

        // お問合せ（確認）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\Contact\ContactConfirmInitializeInteractor::class,
            \App\Business\UseCases\Client\ContactUseCase::class
        );

        // お問合せ（実行）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\Contact\ContactExecuteInteractor::class,
            \App\Business\UseCases\Client\ContactUseCase::class
        );

        // お問合せ（完了）
        $this->app->singleton(
            \App\Business\Interfaces\Interactors\Client\Contact\ContactCompleteInitializeInteractor::class,
            \App\Business\UseCases\Client\ContactUseCase::class
        );

        // 会員CSV取込み
        $this->app->singleton(
            MemberCsvImportInteractor::class,
            MemberCsvImportUseCase::class
        );

        /********************************************************************
         * Admin
         ********************************************************************/
        // 運営会社を認証する
        $this->app->singleton(
            AdminAuthenticationInitializeInteractor::class,
            AdminAuthenticationUseCase::class
        );
        $this->app->singleton(
            AdminAuthenticationLoginInteractor::class,
            AdminAuthenticationUseCase::class
        );
        $this->app->singleton(
            AdminAuthenticationCheckInteractor::class,
            AdminAuthenticationUseCase::class
        );
        $this->app->singleton(
            AdminAuthenticationLogoutInteractor::class,
            AdminAuthenticationUseCase::class
        );

        // ログアウトする
        $this->app->singleton(
            AdminLogoutInteractor::class,
            AdminLogoutUseCase::class
        );

        // 企業を一覧する
        $this->app->singleton(
            CompanyListInitializeInteractor::class,
            CompanyListUseCase::class
        );
        $this->app->singleton(
            CompanyListSearchInteractor::class,
            CompanyListUseCase::class
        );

        // 企業を登録する
        $this->app->singleton(
            CompanyCreateInitializeInteractor::class,
            CompanyCreateUseCase::class
        );

        $this->app->singleton(
            CompanyCreateStoreInteractor::class,
            CompanyCreateUseCase::class
        );

        // 企業を変更する
        $this->app->singleton(
            CompanyEditInitializeInteractor::class,
            CompanyEditUseCase::class
        );
        $this->app->singleton(
            CompanyEditUpdateInteractor::class,
            CompanyEditUseCase::class
        );

        // 会員を一覧する
        $this->app->singleton(
            MemberListInitializeInteractor::class,
            MemberListUseCase::class
        );
        $this->app->singleton(
            MemberListSearchInteractor::class,
            MemberListUseCase::class
        );

        // 会員を登録する
        $this->app->singleton(
            MemberCreateInitializeInteractor::class,
            MemberCreateUseCase::class
        );
        $this->app->singleton(
            MemberCreateStoreInteractor::class,
            MemberCreateUseCase::class
        );

        // 会員を変更する
        $this->app->singleton(
            MemberEditInitializeInteractor::class,
            MemberEditUseCase::class
        );
        $this->app->singleton(
            MemberEditUpdateInteractor::class,
            MemberEditUseCase::class
        );

        // 会員を削除する
        $this->app->singleton(
            MemberDeleteInteractor::class,
            MemberDeleteUseCase::class
        );

        // 求人登録
        $this->app->singleton(
            JobApplicationCreateInitializeInteractor::class,
            JobApplicationCreateUseCase::class
        );
        $this->app->singleton(
            JobApplicationCreateStoreInteractor::class,
            JobApplicationCreateUseCase::class
        );

        // 求人変更
        $this->app->singleton(
            JobApplicationEditInitializeInteractor::class,
            JobApplicationEditUseCase::class
        );
        $this->app->singleton(
            JobApplicationEditUpdateInteractor::class,
            JobApplicationEditUseCase::class
        );

        // 求人変更(求人をプレビューする)
        $this->app->singleton(
            JobApplicationPreviewInitializeInteractor::class,
            JobApplicationPreviewUseCase::class
        );

        // 面接予約を一覧する
        $this->app->singleton(
            InterviewAppointmentListInitializeInteractor::class,
            InterviewAppointmentListUseCase::class
        );
        $this->app->singleton(
            InterviewAppointmentListSearchInteractor::class,
            InterviewAppointmentListUseCase::class
        );

        // 面接予約を登録する
        $this->app->singleton(
            InterviewAppointmentCreateInitializeInteractor::class,
            InterviewAppointmentCreateUseCase::class
        );
        $this->app->singleton(
            InterviewAppointmentCreateStoreInteractor::class,
            InterviewAppointmentCreateUseCase::class
        );

        // 面接予約をキャンセルする
        $this->app->singleton(
            InterviewAppointmentCancelRevokeInteractor::class,
            InterviewAppointmentCancelUseCase::class
        );

        // 面接予約を参照する
        $this->app->singleton(
            InterviewAppointmentDetailShowInteractor::class,
            InterviewAppointmentDetailUseCase::class
        );

        // ビデオ通話履歴を一覧する
        $this->app->singleton(
            AdminVideoInterviewListInitializeInteractor::class,
            AdminVideoInterviewListUseCase::class
        );
        $this->app->singleton(
            AdminVideoInterviewListSearchInteractor::class,
            AdminVideoInterviewListUseCase::class
        );

        // 企業別ビデオ通話を一覧する
        $this->app->singleton(
            VideoInterviewCompanyListInitializeInteractor::class,
            VideoInterviewCompanyListUseCase::class
        );
        $this->app->singleton(
            VideoInterviewCompanyListSearchInteractor::class,
            VideoInterviewCompanyListUseCase::class
        );

        // 会員別ビデオ通話を一覧する
        $this->app->singleton(
            VideoInterviewMemberListInitializeInteractor::class,
            VideoInterviewMemberListUseCase::class
        );
        $this->app->singleton(
            VideoInterviewMemberListSearchInteractor::class,
            VideoInterviewMemberListUseCase::class
        );

        //求人を一覧する
        $this->app->singleton(
            JobApplicationListInitializeInteractor::class,
            JobApplicationListUseCase::class
        );

        //求人を検索する
        $this->app->singleton(
            JobApplicationListSearchInteractor::class,
            JobApplicationListUseCase::class
        );

        // 求人を削除する
        $this->app->singleton(
            JobApplicationDeleteInteractor::class,
            JobApplicationDeleteUseCase::class
        );

        // メッセージを一覧する
        $this->app->singleton(
            AdminMessageListInitializeInteractor::class,
            AdminMessageListUseCase::class
        );

        // メッセージを検索する
        $this->app->singleton(
            AdminMessageListSearchInteractor::class,
            AdminMessageListUseCase::class
        );

        // メッセージを参照する
        $this->app->singleton(
            AdminMessageDetailInitializeInteractor::class,
            AdminMessageDetailUseCase::class
        );

        // メッセージステータスを変更する
        $this->app->singleton(
            AdminMessageStatusUpdateInteractor::class,
            AdminMessageDetailUseCase::class
        );
        // 例文を検索する
        $this->app->singleton(
            ModelSentenceListInitializeInteractor::class,
            ModelSentenceListUseCase::class
        );
        $this->app->singleton(
            ModelSentenceListSearchInteractor::class,
            ModelSentenceListUseCase::class
        );
        // 例文を作成する
        $this->app->singleton(
            ModelSentenceCreateInitializeInteractor::class,
            ModelSentenceCreateUseCase::class
        );
        $this->app->singleton(
            ModelSentenceCreateStoreInteractor::class,
            ModelSentenceCreateUseCase::class
        );
        // 例文を変更する
        $this->app->singleton(
            ModelSentenceEditInitializeInteractor::class,
            ModelSentenceEditUseCase::class
        );
        $this->app->singleton(
            ModelSentenceEditUpdateInteractor::class,
            ModelSentenceEditUseCase::class
        );
        // 例文を削除する
        $this->app->singleton(
            ModelSentenceDeleteInteractor::class,
            ModelSentenceDeleteUseCase::class
        );
        /********************************************************************
         * Backend
         ********************************************************************/

        // ジョブ履歴を登録更新する
        $this->app->singleton(
            JobStoreInteractor::class,
            JobHistoryUseCase::class
        );
        $this->app->singleton(
            JobBeginInteractor::class,
            JobHistoryUseCase::class
        );
        $this->app->singleton(
            JobFinishInteractor::class,
            JobHistoryUseCase::class
        );
        $this->app->singleton(
            JobFailedInteractor::class,
            JobHistoryUseCase::class
        );
        $this->app->singleton(
            JobShowInteractor::class,
            JobHistoryUseCase::class
        );
    }
}