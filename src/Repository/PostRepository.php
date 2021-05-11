<?php
/*
 * Post Repository
 */

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * PostRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('l.id', 'DESC');
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('l');
    }

    /**
     * FindByExampleField.
     *
     * @param Value $value
     *
     * @return Post[] Returns an array of Films objects
     */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Post $post Tag entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Post $post): void
    {
        $this->_em->persist($post);
        $this->_em->flush($post);
    }
    // /**
    //  * @return Films[] Returns an array of Films objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Films
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Query Films by name
     *
     * @param null $id
     */
    public function queryById($id = null)
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($id)) {
            $queryBuilder->andWhere('l.id LIKE :id')
                ->setParameter('id', '%' . $id . '%');
        }

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * Query trash by id
     *
     * @param null $id
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryById1($id = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($id)) {
            $queryBuilder->andWhere('l.id LIKE :id')
                ->setParameter('id', '%' . $id . '%');
        }

        return $queryBuilder;
    }
    /**
     * Delete record.
     *
     * @param \App\Entity\Post $post Post entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Post $post)
    {
        $this->_em->remove($post);
        $this->_em->flush($post);
    }
}