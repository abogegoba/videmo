<?php

namespace App\Business\Services;

use App\Business\Interfaces\Gateways\Criteria\GeneralCriteria;
use App\Business\Interfaces\Gateways\Expression\Builders\GeneralExpressionBuilder;
use App\Business\Interfaces\Gateways\Repositories\CompanyAccountRepository;
use  App\Business\Interfaces\Interactors\Client\Common\UseLoggedInCompanyAccountInputPort;
use ReLab\Commons\Wrappers\CriteriaFactory;

/**
 * Trait UseLoggedInCompanyAccountTrait
 *
 * ログイン企業アカウント取得トレイト（UseCase専用）
 *
 * @package App\Business\Services
 */
trait UseLoggedInCompanyAccountTrait
{
    /**
     * @var companyAccountRepository
     */
    private $companyAccountRepository;

    /**
     * UseLoggedInCompanyAccountTrait constructor.
     *
     * @param CompanyAccountRepository $companyAccountRepository
     */
    public function __construct(CompanyAccountRepository $companyAccountRepository)
    {
        $this->companyAccountRepository = $companyAccountRepository;
    }

    /**
     * ログイン済みの企業会員を取得する
     *
     * @param UseLoggedInCompanyAccountInputPort $inputPort
     * @return \App\Domain\Entities\CompanyAccount
     */
    protected function getLoggedInCompanyAccount(UseLoggedInCompanyAccountInputPort $inputPort)
    {
        $loggedInCompanyAccount = $this->companyAccountRepository->findOneByCriteria(
            CriteriaFactory::getInstance()->create(GeneralCriteria::class, GeneralExpressionBuilder::class,
                [
                    "id" => $inputPort->loggedInCompanyAccountId,
                ]
            )
        );
        return $loggedInCompanyAccount;
    }
}