<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function getAllCategoryForApi ()
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 'SELECT cat.name, cat.id, cat.slug COUNT(cat.id) as posts 
        FROM category cat LEFT JOIN topic topic ON cat.id = topic.category_id INNER JOIN blog blog ON topic.id = blog.topics_id 
        WHERE cat.delete_flag IS NULL GROUP BY cat.id LIMIT 4';

        $sqlConsumor = $connection->prepare($sql);
        $resultSet = $sqlConsumor->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();

    }

    /**
     * @return Category[]
     */
    public function getAllCategory ()
    {
        return $this->createQueryBuilder('category')
        ->where('category.delete_flag is NULL')
        ->orderBy('category.id', 'DESC')
        ->getQuery()
        ->getResult();
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
