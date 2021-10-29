<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\Member;
use ReLab\Commons\Interfaces\Criteria;

interface MemberRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return Member[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return Member
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return Member[]
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
     * @param Member|Member[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 削除する
     *
     * @param Member|Member[] $entities
     */
    public function delete($entities): void;

    /**
     * 複数件の値を取得する
     *
     * @param Criteria $criteria
     * @param array $values
     * @return array
     */
    public function findValuesByCriteria($criteria, $values);

    /**
     * 複数件の値を取得する
     *
     * @param Criteria $criteria
     * @param array $valueNames
     * @return array
     */
    public function findValuesWithUserAccountByCriteria($criteria, $valueNames);

    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return Member[]
     */
    public function findByOverSeasCriteria($criteria);
}
