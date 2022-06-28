<?php

namespace App\Services\Admin;

use App\ServiceInterfaces\Admin\BlogServiceInterface;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Carbon\Carbon;

class BlogService extends AbstractController implements BlogServiceInterface
{

  protected $blogRepository;

  protected $categoryRepository;

  protected $entityManager;

  public function __construct(
    BlogRepository $blogRepository, 
    EntityManagerInterface $entityManager, 
    CategoryRepository $categoryRepository
  )
  {
    $this->blogRepository = $blogRepository;
    $this->entityManager = $entityManager;
    $this->categoryRepository = $categoryRepository;
  }

  public function getAllBlogsByQueryBuilder()
  {
    $queryBuilder = $this->blogRepository->createQueryBuilder('blog')
      ->where('blog.delete_flag is NULL')
      ->orderBy('blog.id', 'DESC')
      ->getQuery();
    return $queryBuilder;
  }

  public function countTotalBLogs()
  {
    $totalBlogs = $this->blogRepository->createQueryBuilder("blog")
      ->select("count(blog.id)")
      ->getQuery()
      ->getSingleScalarResult(); //use this function to get only scalar result 
    return $totalBlogs;
  }

  public function countTodayBlogs()
  {
    $today = Carbon::today()->setTimeZone($this->getParameter('timezone'))->toDateString();
  
    $todayBlogs = $this->blogRepository->createQueryBuilder("blog")
      ->select("count(blog.id)")
      ->where("blog.created_at like :today ")
      ->setParameter("today", $today.'%')
      ->getQuery()
      ->getSingleScalarResult();
    return $todayBlogs;
  }

  public function countTotalViews()
  {
    $totalViews = $this->blogRepository->createQueryBuilder("blog")
      ->select("sum(blog.total_views)")
      ->getQuery()
      ->getSingleScalarResult();
    return $totalViews;
  }

  public function countTodayViews()
  {
    $todayViews = $this->blogRepository->createQueryBuilder("blog")
      ->select("sum(blog.today_views)")
      ->getQuery()
      ->getSingleScalarResult();
    return $todayViews;
  }

  public function createNewBlog($data, $createdBy, $fileName)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();

      $blog = new Blog();
      $blog->setTitle($data->getTitle());
      $blog->setDescription($data->getDescription());
      $blog->setContent($data->getContent());
      $blog->setPlainContent($this->decodeBlogContent($data->getContent()));
      $blog->setMinRead($data->getMinRead());
      $blog->setNotificationEmail($this->getParameter('notification_email_unsend'));
      $blog->setSlug($this->titleToSlug($data->getTitle()));
      $blog->setCreatedBy($createdBy);
      $blog->setTopics($data->getTopics());

      //get category info
      $cat_id = $data->getTopics()->getCategory()->getId();
      $category = $this->categoryRepository->findOneBy([
        'id' => $cat_id,
        'delete_flag' => null
      ]);

      $blog->setCategory($category);
      
      $blog->setThumbnail($fileName);

      foreach ($data->getTags() as $tag) {
        $blog->addTag($tag);
      }

      $this->entityManager->persist($blog);
      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  public function setThumbnailByBlogId ($id)
  {
    $blog = $this->blogRepository->find($id);
    $blog->setThumbnail(
      new File($this->getParameter('blog_thumbnail_directory').'/'.$blog->getThumbnail())
    );
  }

  public function revertThumbnail ($id) {
    $blog = $this->blogRepository->find($id);
    /* dd($blog->getThumbnail()); */
    $blog->setThumbnail($blog->getThumbnail());
  }

  public function getBlogById ($id)
  {
      $blog = $this->blogRepository->find($id);
      return $blog;
  }

  public function updateBlog ($id, $data, $lastModifiedBy, $fileName)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();

      $blog = $this->entityManager->getRepository(Blog::class)->find($id);
      $blog->setTitle($data->getTitle());
      $blog->setDescription($data->getDescription());
      $blog->setContent($data->getContent());
      $blog->setPlainContent($this->decodeBlogContent($data->getContent()));
      $blog->setMinRead($data->getMinRead());
      $blog->setLastModifiedBy($lastModifiedBy);
      $blog->setSlug($this->titleToSlug($data->getTitle()));
      $blog->setTopics($data->getTopics());

      //get category info
      $cat_id = $data->getTopics()->getCategory()->getId();
      $category = $this->categoryRepository->findOneBy([
        'id' => $cat_id,
        'delete_flag' => null
      ]);

      $blog->setCategory($category);

      $blog->setThumbnail($fileName);

      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  public function deleteBlog ($id)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();

      $blog = $this->entityManager->getRepository(Blog::class)->find($id);
      $blog->setDeleteFlag($this->getParameter('delete_flag.deleted'));

      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  /* change notification email status to 1 */
  public function changeNotificationEmail($id)
  {
    $blog = $this->entityManager->getRepository(Blog::class)->find($id);
    $blog->setNotificationEmail($this->getParameter('notification_email_sent'));

    $this->entityManager->flush();
  }

  /* create slug from title */
  public function titleToSlug($title)
  {
    $title = trim(mb_strtolower($title));
    $title = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $title);
    $title = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $title);
    $title = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $title);
    $title = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $title);
    $title = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $title);
    $title = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $title);
    $title = preg_replace('/(đ)/', 'd', $title);
    $title = preg_replace('/[^a-z0-9-\s]/', '', $title);
    $title = preg_replace('/([\s]+)/', '-', $title);
    return $title;
  }

  public function searchBlog($keyword)
  {
    try {
      $queryBuilder = $this->blogRepository->createQueryBuilder("blog");

      //use FullTextSearch match against of sql
      $queryBuilder->where('MATCH_AGAINST(blog.plain_content) AGAINST(:keyword boolean)>0')->setParameter('keyword', $keyword);
      $query = $queryBuilder->getQuery();
      return $query;
    } catch (\Exception $e) {
      return false;
    }
  }

  //strip all html tags when creating html document by ckeditor 
  public function decodeBlogContent($content) {
    $content = strip_tags(html_entity_decode($content));
    return $content;
  }

  public function getDeletedBlogs()
  {
    $blogs = $this->blogRepository->createQueryBuilder('blog')
      ->where('blog.delete_flag = 1')
      ->getQuery();

    return $blogs;
  }

  public function restoreDeletedBlog($id)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();
      $blog = $this->entityManager->getRepository(Blog::class)->find($id);

      $blog->setDeleteFlag(null);
      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }
}
