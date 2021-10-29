<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\UploadedFile;
use App\Domain\Entities\UserAccount;
use ReLab\Commons\Interfaces\Criteria;

interface UploadedFileRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return UploadedFile[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return UploadedFile
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return UploadedFile[]
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
     * @param UploadedFile|UploadedFile[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;

    /**
     * 削除する
     *
     * @param UploadedFile|UploadedFile[]] $entities
     */
    public function delete($entities): void;
}