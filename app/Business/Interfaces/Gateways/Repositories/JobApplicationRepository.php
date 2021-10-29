<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\JobApplication;
use ReLab\Commons\Interfaces\Criteria;

interface JobApplicationRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return JobApplication[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return JobApplication
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return JobApplication[]
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
     * @param JobApplication|JobApplication[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 削除する
     *
     * @param JobApplication|JobApplication[] $entities
     */
    public function delete($entities): void;
}