<?php

namespace App\Providers;

use App\Business\Interfaces\Gateways\Repositories\CareerRepository;
use App\Business\Interfaces\Gateways\Repositories\CertificationRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyRepository;
use App\Business\Interfaces\Gateways\Repositories\CompanyUploadedFileRepository;
use App\Business\Interfaces\Gateways\Repositories\InterviewAppointmentRepository;
use App\Business\Interfaces\Gateways\Repositories\JobApplicationRepository;
use App\Business\Interfaces\Gateways\Repositories\JobHistoryRepository;
use App\Business\Interfaces\Gateways\Repositories\JobTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\LanguageAndCertificationRepository;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\MessageRepository;
use App\Business\Interfaces\Gateways\Repositories\ModelSentenceRepository;
use App\Business\Interfaces\Gateways\Repositories\OperatingCompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\PrefectureRepository;
use App\Business\Interfaces\Gateways\Repositories\SchoolRepository;
use App\Business\Interfaces\Gateways\Repositories\SelfIntroductionRepository;
use App\Business\Interfaces\Gateways\Repositories\TagRepository;
use App\Business\Interfaces\Gateways\Repositories\BusinessTypeRepository;
use App\Business\Interfaces\Gateways\Repositories\UploadedFileRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\VideoCallHistoryRepository;
use App\Domain\Entities\Career;
use App\Domain\Entities\Certification;
use App\Domain\Entities\Company;
use App\Domain\Entities\CompanyAccount;
use App\Domain\Entities\CompanyUploadedFile;
use App\Domain\Entities\InterViewAppointment;
use App\Domain\Entities\JobApplication;
use App\Domain\Entities\JobHistory;
use App\Domain\Entities\JobType;
use App\Domain\Entities\LanguageAndCertification;
use App\Domain\Entities\Member;
use App\Domain\Entities\Message;
use App\Domain\Entities\ModelSentence;
use App\Domain\Entities\OperatingCompanyAccount;
use App\Domain\Entities\Prefecture;
use App\Domain\Entities\School;
use App\Domain\Entities\SelfIntroduction;
use App\Domain\Entities\Tag;
use App\Domain\Entities\BusinessType;
use App\Domain\Entities\UploadedFile;
use App\Domain\Entities\UserAccount;
use App\Domain\Entities\VideoCallHistory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
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
        // 業種
        $this->app->singleton(
            BusinessTypeRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(BusinessType::class);
            }
        );

        // 職種
        $this->app->singleton(
            JobTypeRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(JobType::class);
            }
        );

        // 都道府県
        $this->app->singleton(
            PrefectureRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(Prefecture::class);
            }
        );

        // 求人
        $this->app->singleton(
            JobApplicationRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(JobApplication::class);
            }
        );

        // 会員
        $this->app->singleton(
            MemberRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(Member::class);
            }
        );

        // 経歴
        $this->app->singleton(
            CareerRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(Career::class);
            }
        );

        // 自己紹介
        $this->app->singleton(
            SelfIntroductionRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(SelfIntroduction::class);
            }
        );

        // 学校
        $this->app->singleton(
            SchoolRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(School::class);
            }
        );

        // 語学・資格
        $this->app->singleton(
            LanguageAndCertificationRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(LanguageAndCertification::class);
            }
        );

        // タグ
        $this->app->singleton(
            TagRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(Tag::class);
            }
        );

        // 保有資格・検定
        $this->app->singleton(
            CertificationRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(Certification::class);
            }
        );

        // 企業
        $this->app->singleton(
            CompanyRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(Company::class);
            }
        );

        // ユーザーアカウント
        $this->app->singleton(
            UserAccountRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(UserAccount::class);
            }
        );

        // アップロードファイル
        $this->app->singleton(
            UploadedFileRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(UploadedFile::class);
            }
        );

        // 企業アップロードファイル
        $this->app->singleton(
            CompanyUploadedFileRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(CompanyUploadedFile::class);
            }
        );

        // ジョブ履歴
        $this->app->singleton(
            JobHistoryRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(JobHistory::class);
            }
        );

        // メッセージ
        $this->app->singleton(
            MessageRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(Message::class);
            }
        );

        // 面接予約
        $this->app->singleton(
            InterviewAppointmentRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(InterviewAppointment::class);
            }
        );

        // ビデオ通話履歴
        $this->app->singleton(
            VideoCallHistoryRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(VideoCallHistory::class);
            }
        );

        // 企業アカウント
        $this->app->singleton(
            CompanyAccountRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(CompanyAccount::class);
            }
        );

        // 運営会社アカウント
        $this->app->singleton(
            OperatingCompanyAccountRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(OperatingCompanyAccount::class);
            }
        );

        // メッセージ例文
        $this->app->singleton(
            ModelSentenceRepository::class,
            function (Application $app) {
                /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
                $entityManager = $app->make(\Doctrine\ORM\EntityManagerInterface::class);
                return $entityManager->getRepository(ModelSentence::class);
            }
        );
    }
}
