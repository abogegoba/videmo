<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\JobHistory;
use ReLab\Commons\Interfaces\Criteria;

/**
 * Interface JobHistoryRepository
 *
 * @package App\Business\Interfaces\Gateways\Repositories
 */
interface JobHistoryRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return JobHistory[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return JobHistory
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return JobHistory[]
     */
    public function findAll();

    /**
     * カウントする
     *
     * @param JobHistory $criteria
     * @return int
     */
    public function countByCriteria($criteria): int;

    /**
     * 登録/変更する
     *
     * @param JobHistory|JobHistory[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 削除する
     *
     * @param JobHistory|JobHistory[] $entities
     */
    public function delete($entities): void;
}