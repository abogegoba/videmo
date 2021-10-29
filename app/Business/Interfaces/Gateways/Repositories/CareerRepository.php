<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\Career;
use ReLab\Commons\Interfaces\Criteria;

interface CareerRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return Career[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return Career
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return Career[]
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
     * @param Career|Career[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 物理削除する
     *
     * @param Career|Career[] $entities
     */
    public function physicalDelete($entities): void;
}