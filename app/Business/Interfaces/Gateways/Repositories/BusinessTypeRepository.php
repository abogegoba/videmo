<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\BusinessType;
use ReLab\Commons\Interfaces\Criteria;

interface BusinessTypeRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return BusinessType[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return BusinessType
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return BusinessType[]
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
     * 複数件の値を取得する
     *
     * @param Criteria $criteria
     * @param array $values
     * @return array
     */
    public function findValuesByCriteria($criteria, $values);
}