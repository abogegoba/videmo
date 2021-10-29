<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\OperatingCompanyAccount;
use ReLab\Commons\Interfaces\Criteria;

interface OperatingCompanyAccountRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return OperatingCompanyAccount[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return OperatingCompanyAccount
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return OperatingCompanyAccount[]
     */
    public function findAll();

    /**
     * カウントする
     *
     * @param Criteria $criteria
     * @return int
     */
    public function countByCriteria($criteria): int;

    /**
     * 登録/変更する
     *
     * @param OperatingCompanyAccount|OperatingCompanyAccount[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;
}