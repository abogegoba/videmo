<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\SelfIntroduction;
use ReLab\Commons\Interfaces\Criteria;

interface SelfIntroductionRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return SelfIntroduction[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return SelfIntroduction
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return SelfIntroduction[]
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
     * @param SelfIntroduction|SelfIntroduction[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 物理削除する
     *
     * @param SelfIntroduction|SelfIntroduction[] $entities
     */
    public function physicalDelete($entities): void;
}