<?php

namespace App\Business\UseCases\Client;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationCheckInputPort;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationCheckOutputPort;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationInitializeInteractor;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationLoginInputPort;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationLoginInteractor;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationLoginOutputPort;
use App\Business\Interfaces\Interactors\Client\ClientAuthentication\ClientAuthenticationLogoutInteractor;
use App\Domain\Entities\Company;
use App\Domain\Entities\UserAccount;
use App\Domain\Model\ClientAuthentication;
use App\Exceptions\AuthenticationFailedException;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\FatalException;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Class ClientAuthenticationUseCase
 *
 * 会員を認証する
 *
 * @package App\Business\UseCases\Client
 */
class ClientAuthenticationUseCase implements ClientAuthenticationInitializeInteractor, ClientAuthenticationLoginInteractor, ClientAuthenticationCheckInteractor, ClientAuthenticationLogoutInteractor
{
    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     *
     * @var CompanyAccountRepository
     */
    private $companyAccountRepository;

    /**
     * AccountAuthenticateUseCase constructor.
     *
     * @param UserAccountRepository $userAccountRepository
     * @param CompanyAccountRepository $companyAccountRepository
     */
    public function __construct(
        UserAccountRepository $userAccountRepository,
        CompanyAccountRepository $companyAccountRepository
    ) {
        $this->userAccountRepository = $userAccountRepository;
        $this->companyAccountRepository = $companyAccountRepository;
    }

    /**
     * 初期化する
     */
    public function initialize(): void
    {
        // 企業会員の認証を削除する
        ClientAuthentication::removeSession();
    }

    /**
     * ログインする
     *
     * @param ClientAuthenticationLoginInputPort $inputPort
     * @param ClientAuthenticationLoginOutputPort $outputPort
     * @throws AuthenticationFailedException
     */
    public function login(ClientAuthenticationLoginInputPort $inputPort, ClientAuthenticationLoginOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        try {
            // ログインID及びパスワードに該当するユーザーアカウントを取得する
            $companyAccount = $this->companyAccountRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        'userAccount.mailAddress' => $inputPort->mailAddress,
                        'company.status' => Company::STATUS_VISIBLE
                    ],
                    [
                        "forUpdate" => true
                    ]
                )
            );

            $userAccount = $companyAccount->getUserAccount();
            if ($userAccount->getPassword() !== $inputPort->password) {
                throw new FatalException("not_same_password");
            };

            // 取得したユーザーアカウトを使用して企業会員認証を作成する
            $outputPort->clientAuthentication = $this->createClientAuthenticationFromUserAccount($userAccount);

            // 最終ログイン日時を更新する
            $userAccount->renewLastLoginDateTime();
            $this->userAccountRepository->saveOrUpdate($userAccount);

        } catch (\Exception $e) {
            // 例外が発生した場合認証失敗例外を発生させる
            throw new AuthenticationFailedException("front");
        }

        //ログ出力
        Log::infoOut();
    }

    /**
     * 認証を確認する
     *
     * @param ClientAuthenticationCheckInputPort $inputPort
     * @param ClientAuthenticationCheckOutputPort $outputPort
     * @throws FatalBusinessException
     * @throws FatalException
     */
    public function check(ClientAuthenticationCheckInputPort $inputPort, ClientAuthenticationCheckOutputPort $outputPort): void
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

        // 取得したユーザーアカウトを使用して企業会員認証を作成する
        $outputPort->clientAuthentication = $this->createClientAuthenticationFromUserAccount($userAccount);

        //ログ出力
        Log::infoOut();
    }

    /**
     * ログアウトする
     */
    public function logout(): void
    {
        //ログ出力
        Log::infoIn();

        // 会員認証を削除する
        ClientAuthentication::removeSession();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 企業会員認証を作成する
     *
     * @param UserAccount $userAccount
     * @return ClientAuthentication|null
     * @throws FatalException
     */
    private function createClientAuthenticationFromUserAccount(UserAccount $userAccount): ?ClientAuthentication
    {
        $companyAccount = $userAccount->getCompanyAccount();
        // 企業会員ではない場合
        if (!isset($companyAccount)) {
            throw new FatalException("is_not_companyAccount");
        }

        // 企業認証を作成する
        return ClientAuthentication::createSession($userAccount->getCompanyAccount());
    }
}