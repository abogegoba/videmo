<?php

namespace ReLab\Doctrine\Repositories;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use ReLab\Commons\Exceptions\ObjectNotFoundException;
use ReLab\Commons\Interfaces\Pager;
use ReLab\Doctrine\Criteria\DoctrineCriteria;

/**
 * Class DoctrineRepository
 *
 * @package ReLab\Doctrine\Repositories
 */
class DoctrineRepository extends EntityRepository
{
    /**
     * 複数件取得する
     *
     * @param DoctrineCriteria $criteria
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function findByCriteria($criteria)
    {
        $queryBuilder = $this->createQueryBuilderByCriteria($criteria);
        $entityAlias = $criteria->expressionBuilder()->entityAlias();
        $pager = $criteria->pager();
        if (isset($pager)) {
            $queryBuilder = $this->paging($pager, $entityAlias, $queryBuilder);
        }
        $queryBuilder->select($entityAlias);
        return $this->setLockModeToQueryByCriteria($queryBuilder->getQuery(), $criteria)->getResult();
    }

    /**
     * １件取得する
     *
     * @param DoctrineCriteria $criteria
     * @return mixed
     * @throws ObjectNotFoundException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function findOneByCriteria($criteria)
    {
        $queryBuilder = $this->createQueryBuilderByCriteria($criteria);
        $entityAlias = $criteria->expressionBuilder()->entityAlias();
        $queryBuilder->select($entityAlias);
        $queryBuilder->setMaxResults(1);
        $result = $this->setLockModeToQueryByCriteria($queryBuilder->getQuery(), $criteria)->getResult();
        if (empty($result)) {
            throw new ObjectNotFoundException($this->getEntityName());
        } else {
            return $result[0];
        }
    }

    /**
     * 複数件の値を取得する
     *
     * @param DoctrineCriteria $criteria
     * @param array $valueNames
     * @return mixed
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function findValuesByCriteria($criteria, $valueNames)
    {
        $queryBuilder = $this->createQueryBuilderByCriteria($criteria);
        $entityAlias = $criteria->expressionBuilder()->entityAlias();
        $selects = [];
        foreach ($valueNames as $valueName) {
            $selects[] = $entityAlias . "." . $valueName;
        }
        $select = implode(",", $selects);
        $queryBuilder->select($select);
        return $this->setLockModeToQueryByCriteria($queryBuilder->getQuery(), $criteria)->getResult();
    }

    /**
     * カウントする
     *
     * @param DoctrineCriteria $criteria
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByCriteria($criteria): int
    {
        $queryBuilder = $this->createQueryBuilderByCriteria($criteria);
        $entityAlias = $criteria->expressionBuilder()->entityAlias();
        $pager = $criteria->pager();
        if (isset($pager)) {
            $queryBuilder = $this->paging($pager, $entityAlias, $queryBuilder);
            if (!empty($pager->notDistinct) && $pager->notDistinct === true) {
                $queryBuilder->select("count($entityAlias)");
            } else {
                $queryBuilder->select("count(distinct($entityAlias))");
            }
        } else {
            $queryBuilder->select("count(distinct($entityAlias))");
        }
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * 登録/変更する
     *
     * @param object|array $entities
     * @param bool $instantly
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveOrUpdate($entities, bool $instantly = false): void
    {
        $entityManager = $this->getEntityManager();
        if (is_array($entities)) {
            foreach ($entities as $entity) {
                $entityManager->persist($entity);
            }
        } else {
            $entityManager->persist($entities);
        }
        if ($instantly) {
            $entityManager->flush($entities);
        }
    }

    /**
     * 削除する
     *
     * @param object|array $entities
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete($entities): void
    {
        $entityManager = $this->getEntityManager();
        if (is_array($entities)) {
            foreach ($entities as $entity) {
                $entityManager->remove($entity);
            }
        } else {
            $entityManager->remove($entities);
        }
    }

    /**
     * ページングする
     *
     * @param Pager $pager
     * @param string $countAlias
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function paging($pager, string $countAlias, QueryBuilder $queryBuilder): QueryBuilder
    {
        // 全件数カウント
        $countQueryBuilder = clone $queryBuilder;
        $notDistinct = $pager->notDistinct;
        if (!empty($notDistinct) && $notDistinct === true) {
            $countQueryBuilder->select("count($countAlias)");
        } else {
            $countQueryBuilder->select("count(distinct($countAlias))");
        }
        $countQueryBuilder->resetDQLPart('orderBy');
        $countQueryBuilder->resetDQLPart('groupBy');
        $allCount = $countQueryBuilder->getQuery()->getSingleScalarResult();
        $pager->allCount = intval($allCount);
        unset($countQueryBuilder);

        // リミット設定
        $limit = $pager->limit;
        if (!empty($limit) && is_numeric($limit) && $limit > 0) {
            $pager->limit = intval($limit);
            $index = $pager->index;
            if (!empty($index) && is_numeric($index)) {
                $pager->index = intval($index);
            } else {
                $pager->index = 1;
            }
            $offset = ($pager->index - 1) * $pager->limit;
            $queryBuilder->setFirstResult($offset);
            $queryBuilder->setMaxResults($pager->limit);
        }

        return $queryBuilder;
    }

    /**
     * 指定したCriteriaからQueryBuilderを作成する
     *
     * @param DoctrineCriteria $criteria
     * @return QueryBuilder
     */
    protected function createQueryBuilderByCriteria(DoctrineCriteria $criteria): QueryBuilder
    {
        // デフォルトのQueryBuilderを作成する
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        // From句を指定する
        $expressionBuilder = $criteria->expressionBuilder();
        $queryBuilder->from($this->getEntityName(), $expressionBuilder->entityAlias());

        // Where句を指定する
        $expression = $expressionBuilder->build();
        if (isset($expression)) {
            $queryBuilder->where($expression);
        }

        // OrderBy句を指定する
        $orderBy = $criteria->orderBy();
        if (isset($orderBy)) {
            foreach ($orderBy as $field => $value) {
                $queryBuilder->addOrderBy($expressionBuilder->convertAliasField($field, true), $value);
            }
        }

        // GroupBy句を指定する
        $groupBy = $criteria->groupBy();
        if (isset($groupBy)) {
            foreach ($groupBy as $field) {
                $queryBuilder->addGroupBy($expressionBuilder->convertAliasField($field, true));
            }
        }

        // InnerJoin句を指定する
        foreach ($expressionBuilder->getInnerJoins() as $alias => $field) {
            $queryBuilder->innerJoin($field, $alias);
        }

        // LeftJoin句を指定する
        foreach ($expressionBuilder->getLeftJoins() as $alias => $field) {
            $queryBuilder->leftJoin($field, $alias);
        }

        // パラメータを設定する
        $queryBuilder->setParameters($expressionBuilder->getParameters());

        return $queryBuilder;
    }

    /**
     * ロックモードを設定する
     *
     * @param Query $query
     * @param DoctrineCriteria $criteria
     * @return Query
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    protected function setLockModeToQueryByCriteria(Query $query, DoctrineCriteria $criteria): Query
    {
        // ForUpdate指定が「true」の場合に更新用のロックモードを設定する
        if ($criteria->forUpdate() === true) {
            $query->setLockMode(LockMode::PESSIMISTIC_WRITE);
        }
        return $query;
    }

    /**
     * 物理削除する
     *
     * ※doctrine側で提供されているdisableが使用できない為コメントアウト
     * 2回removeを行うと物理削除となるdoctrineの不具合を利用
     *
     * @param object|array $entities
     * @throws \Doctrine\ORM\ORMException
     */
    public function physicalDelete($entities): void
    {

        $entityManager = $this->getEntityManager();
//        $filters = $entityManager->getFilters();
//        $filters->disable('soft-deleteable');
        if (is_array($entities)) {
            foreach ($entities as $entity) {
                // 一度論理削除を行う
                $entityManager->remove($entity);
                $entityManager->flush();
                // 物理削除
                $entityManager->remove($entity);
            }
        } else {
            // 一度論理削除を行う
            $entityManager->remove($entities);
            $entityManager->flush();
            // 物理削除
            $entityManager->remove($entities);
        }
//        $filters->enable('soft-deleteable');
    }
}