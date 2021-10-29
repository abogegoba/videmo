<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\School;
use ReLab\Commons\Interfaces\Criteria;

interface SchoolRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return School[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return School
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return School[]
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
     * @param School|School[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;
}