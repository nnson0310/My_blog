<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function getLatestBlogs($limit)
    {
        return $this->createQueryBuilder('blog')
                ->where('blog.delete_flag is NULL')
                ->orderBy('blog.id', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
    }

    public function getLatestBlogInfo($limit)
    {
        return $this->createQueryBuilder('blog')
                ->select('blog.id', 'blog.title', 'blog.description', 'blog.thumbnail', 'blog.created_at', 'blog.min_read', 'blog.slug')
                ->where('blog.delete_flag is NULL')
                ->orderBy('blog.id', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getArrayResult();
    }

    /* public function getBlogInfo($id)
    {
        return $this->createQueryBuilder('blog')
            ->where('blog.delete_flag is NULL')
            ->andWhere('blog.id = :id ')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

    } */

    // /**
    //  * @return Blog[] Returns an array of Blog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
