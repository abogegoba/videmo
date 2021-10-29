<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\UserAccount;
use ReLab\Commons\Interfaces\Criteria;

interface UserAccountRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return UserAccount[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return UserAccount
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return UserAccount[]
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
     * @param UserAccount|UserAccount[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;
}