<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\OperatingCompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckInputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationCheckOutputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationInitializeInputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationInitializeInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationInitializeOutputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationLoginInputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationLoginInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationLoginOutputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationLogoutInputPort;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationLogoutInteractor;
use App\Business\Interfaces\Interactors\Admin\AdminAuthentication\AdminAuthenticationLogoutOutputPort;
use App\Domain\Entities\UserAccount;
use App\Domain\Model\AdminAuthentication;
use App\Exceptions\AuthenticationFailedException;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use Config;


/**
 * Class MemberAuthenticationUseCase
 *
 * 運営会社を認証する
 *
 * @package App\Business\UseCases\Front
 */
class AdminAuthenticationUseCase implements AdminAuthenticationInitializeInteractor, AdminAuthenticationLoginInteractor, AdminAuthenticationCheckInteractor, AdminAuthenticationlogoutInteractor
{
    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * @var OperatingCompanyAccountRepository
     */
    private $operatingCompanyAccountRepository;

    /**
     * AccountAuthenticateUseCase constructor.
     *
     * @param UserAccountRepository $userAccountRepository
     * @param OperatingCompanyAccountRepository $operatingCompanyAccountRepository
     */
    public function __construct(
        UserAccountRepository $userAccountRepository,
        OperatingCompanyAccountRepository $operatingCompanyAccountRepository
    ) {
        $this->userAccountRepository = $userAccountRepository;
        $this->operatingCompanyAccountRepository = $operatingCompanyAccountRepository;
    }

    /**
     * 初期化する
     */
    public function initialize(AdminAuthenticationInitializeInputPort $inputPort, AdminAuthenticationInitializeOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 運営会社の認証を削除する
        AdminAuthentication::removeSession();

        //ログ出力
        Log::infoOut();
    }

    /**
     * ログインする
     *
     * @param AdminAuthenticationLoginInputPort $inputPort
     * @param AdminAuthenticationLoginOutputPort $outputPort
     * @throws AuthenticationFailedException
     */
    public function login(AdminAuthenticationLoginInputPort $inputPort, AdminAuthenticationLoginOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        try {
            // ログインID及びパスワードに該当するユーザーアカウントを取得する
            $operatingCompanyAccount = $this->operatingCompanyAccountRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        'userAccount.mailAddress' => $inputPort->mailAddress,
                        'userAccount.companyAccountId' => null,
                        'userAccount.memberId' => null,
                    ],
                    [
                        "forUpdate" => true
                    ]
                )
            );

            $userAccount = $operatingCompanyAccount->getUserAccount();
            if ($userAccount->getPassword() !== $inputPort->password) {
                throw new FatalException("not_same_password");
            };

            // 取得したユーザーアカウトを使用して会員認証を作成する
            $outputPort->adminAuthentication = $this->createAdminAuthenticationFromUserAccount($userAccount);

            // 最終ログイン日時を更新する
            $userAccount->renewLastLoginDateTime();
            $this->userAccountRepository->saveOrUpdate($userAccount);

        } catch (\Exception $e) {
            // 例外が発生した場合認証失敗例外を発生させる
            throw new AuthenticationFailedException("admin");
        }

        //ログ出力
        Log::infoOut();
    }

    /**
     * 認証を確認する
     *
     * @param AdminAuthenticationCheckInputPort $inputPort
     * @param AdminAuthenticationCheckOutputPort $outputPort
     * @throws FatalException
     */
    public function check(AdminAuthenticationCheckInputPort $inputPort, AdminAuthenticationCheckOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // アカウントIDから対象のユーザーアカウントを取得する
        $userAccount = $this->userAccountRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->userAccountId
                ]
            )
        );

        // 取得したユーザーアカウトを使用して会員認証を作成する
        $outputPort->adminAuthentication = $this->createAdminAuthenticationFromUserAccount($userAccount);

        //ログ出力
        Log::infoOut();
    }

    /**
     * ログアウトする
     */
    public function logout(AdminAuthenticationLogoutInputPort $inputPort, AdminAuthenticationLogoutOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        // 会員認証を削除する
        AdminAuthentication::removeSession();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 運営会社認証を作成する
     *
     * @param UserAccount $userAccount
     * @return AdminAuthentication|null
     * @throws FatalException
     */
    private function createAdminAuthenticationFromUserAccount(UserAccount $userAccount): ?AdminAuthentication
    {
        $operatingCompanyAccount = $userAccount->getOperatingCompanyAccount();
        // 運営会社ではない場合
        if (!isset($operatingCompanyAccount)) {
            throw new FatalException("is_not_operating_company_account");
        }

        // 会員認証を作成する
        return AdminAuthentication::createSession($userAccount->getOperatingCompanyAccount());
    }
}