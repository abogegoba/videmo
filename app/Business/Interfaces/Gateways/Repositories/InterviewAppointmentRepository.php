<?php

namespace App\Business\Interfaces\Gateways\Repositories;

use App\Domain\Entities\InterViewAppointment;
use ReLab\Commons\Interfaces\Criteria;

interface InterviewAppointmentRepository
{
    /**
     * 複数件取得する
     *
     * @param Criteria $criteria
     * @return InterViewAppointment[]
     */
    public function findByCriteria($criteria);

    /**
     * １件取得する
     *
     * @param Criteria $criteria
     * @return InterViewAppointment
     */
    public function findOneByCriteria($criteria);

    /**
     * 全件取得する
     *
     * @return InterViewAppointment[]
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
     * @param InterViewAppointment|InterViewAppointment[] $entities
     * @param bool $instantly
     */
    public function saveOrUpdate($entities, bool $instantly = false): void;
}