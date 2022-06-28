<?php

namespace App\Services\Api;

use App\ServiceInterfaces\Api\BlogServiceInterface;
use App\Repository\BlogRepository;
use App\Repository\TagsRepository;
use App\Entity\Blog;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogService extends AbstractController implements BlogServiceInterface
{

  protected $blogRepository;

  protected $entityManager;

  protected $categoryRepository;

  protected $tagsRepository;

  public function __construct(
    BlogRepository $blogRepository,
    EntityManagerInterface $entityManager,
    CategoryRepository $categoryRepository,
    TagsRepository $tagsRepository
  ) {
    $this->blogRepository = $blogRepository;
    $this->entityManager = $entityManager;
    $this->categoryRepository = $categoryRepository;
    $this->tagsRepository = $tagsRepository;
  }

  /* These are function which call api */

  public function getLatestBlogs()
  {
    $blogs = $this->blogRepository->getLatestBlogs($this->getParameter('blog_limit_results'));
    return $blogs;
  }

  public function getLatestBlogInfo()
  {
    $blogInfo = $this->blogRepository->getLatestBlogInfo($this->getParameter('blog_limit_results'));
    return $blogInfo;
  }

  public function getBlogDetails($id)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();
      $blogDetails = $this->entityManager->getRepository(Blog::class)->find($id);
      $currentTotalViews = $blogDetails->getTotalViews();
      $currentTodayViews = $blogDetails->getTodayViews();
      $blogDetails->setTotalViews($currentTotalViews+=1);
      $blogDetails->setTodayViews($currentTodayViews+=1);
      $this->entityManager->persist($blogDetails);
      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return $blogDetails;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
    
  }

  public function getAllBlogs()
  {
    $queryBuilder = $this->blogRepository->createQueryBuilder('blog')
      ->select('blog.id', 'blog.min_read', 'blog.title', 'blog.description', 'blog.created_at', 'blog.thumbnail')
      ->where('blog.delete_flag is NULL')
      ->orderBy('blog.id', 'DESC')
      ->getQuery();
    return $queryBuilder;
  }

  public function getAllCategoryPosts($catId)
  {
    $query = $this->entityManager->createQuery(
      'SELECT 
      blog.title, 
      blog.id, 
      blog.description, 
      blog.created_at, 
      blog.min_read, 
      blog.thumbnail 
      FROM App\Entity\Blog blog 
      INNER JOIN blog.category cat  
      WHERE cat.id = :cat_id'
    )->setParameter('cat_id', $catId);

    return $query;
  }

  public function getAllTagPosts($slug) {
    $query = $this->blogRepository->createQueryBuilder("blog")
      ->select("blog.id, blog.title, blog.description, blog.created_at, blog.min_read, blog.thumbnail")
      ->leftJoin("blog.tags", "tags")
      ->where("tags.slug = :slug")
      ->setParameter("slug", $slug)
      ->getQuery();

    return $query;
  }

  public function search($keyword)
  {
    $queryBuilder = $this->blogRepository->createQueryBuilder("blog");

    //use FullTextSearch match against of sql
    $queryBuilder->select(
    "blog.title, 
    blog.id, 
    blog.description, 
    blog.created_at, 
    blog.min_read, 
    blog.thumbnail")->where('MATCH_AGAINST(blog.plain_content) AGAINST(:keyword boolean)>0')->setParameter('keyword', $keyword);
    $query = $queryBuilder->getQuery();
    return $query;
  }

  public function getFeaturePosts()
  {
    $conn = $this->entityManager->getConnection();
    $sql = "SELECT blog.id, blog.title, blog.created_at, tags.name As tagName
    FROM blog_tags 
    LEFT JOIN (SELECT * FROM blog WHERE blog.delete_flag IS NULL ORDER BY blog.total_views DESC LIMIT 6) 
    AS blog ON blog_tags.blog_id = blog.id 
    LEFT JOIN tags 
    ON blog_tags.tags_id = tags.id";
    $queryBuilder  = $conn->prepare($sql);
    $queryResult = $queryBuilder->executeQuery()->fetchAllAssociative();

    return $queryResult;
  }

  public function getMostViewPosts()
  {
    $result = $this->blogRepository->createQueryBuilder("blog")
      ->select("blog.id")
      ->setMaxResults(6)
      ->where("blog.delete_flag is NULL")
      ->orderBy("blog.total_views", "DESC")
      ->getQuery()
      ->getArrayResult();

    return $result;
  }
}
