<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\CompanyAccount;
use ReLab\Commons\Interfaces\Criteria;

interface CompanyAccountRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return CompanyAccount[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return CompanyAccount
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return CompanyAccount[]
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
     * @param CompanyAccount|CompanyAccount[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;
}