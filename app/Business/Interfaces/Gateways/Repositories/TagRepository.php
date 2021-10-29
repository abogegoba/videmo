<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\Tag;
use ReLab\Commons\Interfaces\Criteria;

interface TagRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return Tag[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return Tag
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return Tag[]
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
     * @param Tag|Tag[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 削除する
     *
     * @param Tag|Tag[] $entities
     */
    public function delete($entities): void;
}