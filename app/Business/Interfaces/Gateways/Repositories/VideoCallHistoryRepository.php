<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\VideoCallHistory;
use ReLab\Commons\Interfaces\Criteria;

interface VideoCallHistoryRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return VideoCallHistory[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return VideoCallHistory
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return VideoCallHistory[]
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
     * @param VideoCallHistory|VideoCallHistory[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;
}