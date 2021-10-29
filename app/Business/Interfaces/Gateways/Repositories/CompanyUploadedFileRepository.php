<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\CompanyUploadedFile;
use ReLab\Commons\Interfaces\Criteria;

interface CompanyUploadedFileRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return CompanyUploadedFile[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return CompanyUploadedFile
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return CompanyUploadedFile[]
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

    /**
     * 登録/変更する
     *
     * @param CompanyUploadedFile|CompanyUploadedFile[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;
}
