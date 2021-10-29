<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\Company;
use ReLab\Commons\Interfaces\Criteria;

interface CompanyRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return Company[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return Company
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return Company[]
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
     * @param Company|Company[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 複数件の値を取得する
     *
     * @param Criteria $criteria
     * @param array $values
     * @return array
     */
    public function findValuesByCriteria($criteria, $values);
}