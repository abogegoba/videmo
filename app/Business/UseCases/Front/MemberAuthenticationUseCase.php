<?php

namespace App\Business\UseCases\Front;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\MemberRepository;
use App\Business\Interfaces\Gateways\Repositories\UserAccountRepository;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationCheckInputPort;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationCheckInteractor;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationCheckOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationInitializeInteractor;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationLoginInputPort;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationLoginInteractor;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationLoginOutputPort;
use App\Business\Interfaces\Interactors\Front\MemberAuthentication\MemberAuthenticationLogoutInteractor;
use App\Domain\Entities\Member;
use App\Domain\Entities\UserAccount;
use App\Domain\Model\MemberAuthentication;
use App\Exceptions\AuthenticationFailedException;
use App\Utilities\Log;
use ReLab\Commons\Exceptions\FatalBusinessException;
use ReLab\Commons\Exceptions\FatalException;
use ReLab\Commons\Wrappers\CriteriaFactory;
use Config;


/**
 * Class MemberAuthenticationUseCase
 *
 * 会員を認証する
 *
 * @package App\Business\UseCases\Front
 */
class MemberAuthenticationUseCase implements MemberAuthenticationInitializeInteractor, MemberAuthenticationLoginInteractor, MemberAuthenticationCheckInteractor, MemberAuthenticationlogoutInteractor
{
    /**
     * @var UserAccountRepository
     */
    private $userAccountRepository;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * AccountAuthenticateUseCase constructor.
     *
     * @param UserAccountRepository $userAccountRepository
     * @param MemberRepository $memberRepository
     */
    public function __construct(
        UserAccountRepository $userAccountRepository,
        MemberRepository $memberRepository
    ) {
        $this->userAccountRepository = $userAccountRepository;
        $this->memberRepository = $memberRepository;
    }

    /**
     * 初期化する
     */
    public function initialize(): void
    {
        // 会員を認証を削除する
        MemberAuthentication::removeSession();
    }

    /**
     * ログインする
     *
     * @param MemberAuthenticationLoginInputPort $inputPort
     * @param MemberAuthenticationLoginOutputPort $outputPort
     * @throws AuthenticationFailedException
     */
    public function login(MemberAuthenticationLoginInputPort $inputPort, MemberAuthenticationLoginOutputPort $outputPort): void
    {
        //ログ出力
        Log::infoIn();

        try {
            // ログインID及びパスワードに該当するユーザーアカウントを取得する
            $member = $this->memberRepository->findOneByCriteria(
                CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                    [
                        'userAccount.mailAddress' => $inputPort->mailAddress,
                        'status' => Member::STATUS_REAL_MEMBER,
                    ],
                    [
                        "forUpdate" => true
                    ]
                )
            );

            $userAccount = $member->getUserAccount();
            if ($userAccount->getPassword() !== $inputPort->password) {
                throw new FatalException("not_same_password");
            };

            // 取得したユーザーアカウトを使用して会員認証を作成する
            $outputPort->memberAuthentication = $this->createMemberAuthenticationFromUserAccount($userAccount);

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
     * @param MemberAuthenticationCheckInputPort $inputPort
     * @param MemberAuthenticationCheckOutputPort $outputPort
     * @throws FatalBusinessException
     * @throws FatalException
     */
    public function check(MemberAuthenticationCheckInputPort $inputPort, MemberAuthenticationCheckOutputPort $outputPort): void
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
        $outputPort->memberAuthentication = $this->createMemberAuthenticationFromUserAccount($userAccount);

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
        MemberAuthentication::removeSession();

        //ログ出力
        Log::infoOut();
    }

    /**
     * 会員認証を作成する
     *
     * @param UserAccount $userAccount
     * @return MemberAuthentication|null
     * @throws FatalException
     */
    private function createMemberAuthenticationFromUserAccount(UserAccount $userAccount): ?MemberAuthentication
    {
        $member = $userAccount->getMember();
        // 会員ではない場合
        if (!isset($member)) {
            throw new FatalException("is_not_member");
        }
        // 本会員ではない場合
        if ($member->getStatus() !== Member::STATUS_REAL_MEMBER) {
            throw new FatalException("is_not_real_member");
        }

        // 会員認証を作成する
        return MemberAuthentication::createSession($userAccount->getMember());
    }
}