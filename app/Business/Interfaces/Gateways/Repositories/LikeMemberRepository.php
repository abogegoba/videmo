<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\LikeMember;
use ReLab\Commons\Interfaces\Criteria;

interface LikeMemberRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return LikeMember[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return LikeMember
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return LikeMember[]
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
     * @param LikeMember|LikeMember[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 物理削除する
     *
     * @param LikeMember|LikeMember[] $entities
     */
    public function physicalDelete($entities): void;
}
