<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\Certification;
use ReLab\Commons\Interfaces\Criteria;

interface CertificationRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return Certification[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return Certification
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return Certification[]
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
     * @param Certification|Certification[] $entities
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

    /**
     * 物理削除する
     *
     * @param Certification|Certification[] $entities
     */
    public function physicalDelete($entities): void;
}