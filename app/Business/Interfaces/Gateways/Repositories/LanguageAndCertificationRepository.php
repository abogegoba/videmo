<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\LanguageAndCertification;
use ReLab\Commons\Interfaces\Criteria;

interface LanguageAndCertificationRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return LanguageAndCertification[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return LanguageAndCertification
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return LanguageAndCertification[]
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
     * @param LanguageAndCertification|LanguageAndCertification[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;
}