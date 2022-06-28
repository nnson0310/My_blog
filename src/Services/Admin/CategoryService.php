<?php

namespace App\Services\Admin;

use App\Entity\Category;
use App\ServiceInterfaces\Admin\CategoryServiceInterface;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryService extends AbstractController implements CategoryServiceInterface
{

  protected $categoryRepository;

  protected $topicRepository;

  protected $paginator;

  protected $entityManager;

  protected $validator;

  public function __construct(CategoryRepository $categoryRepository, TopicRepository $topicRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager, ValidatorInterface $validator)
  {
    $this->categoryRepository = $categoryRepository;
    $this->paginator = $paginator;
    $this->entityManager = $entityManager;
    $this->validator = $validator;
    $this->topicRepository = $topicRepository;
  }

  public function createNewCategory ($params)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();
      $category = new Category();
      $category->setName(trim($params['cat_name']) );
      $category->setSlug($this->nameToSlug(trim($params['cat_name'])));
      $category->setDescription(trim($params['cat_desc']));
      $violations = $this->validator->validate($category);
      if ($violations && count($violations) > 0) {
        $messages = [];
        foreach ($violations as $key => $violation) {
          $messages[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $messages;
      }
      $this->entityManager->persist($category);
      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return 'Create new category successfully.';
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  public function getAllCategoryByQueryBuilder ()
  {
      $queryBuilder = $this->categoryRepository->createQueryBuilder('category')
        ->where('category.delete_flag is NULL')
        ->orderBy('category.id', 'DESC')
        ->getQuery();
      return $queryBuilder;
  }

  public function getAllCategory ()
  {
    $category = $this->categoryRepository->getAllCategory();
    return $category;
  }

  public function getAllDeletedCategory()
  {
    $queryBuilder = $this->categoryRepository->createQueryBuilder('category')
        ->where('category.delete_flag is NOT NULL')
        ->orderBy('category.id', 'DESC')
        ->getQuery();
      return $queryBuilder;
  }

  public function restoreCategory($params)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();
      $category = $this->entityManager->getRepository(Category::class)->find($params);

      $category->setDeleteFlag(null);
      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  public function deleteCategoryById($cat_id)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();
      $category = $this->entityManager->getRepository(Category::class)->find($cat_id);
      $topics = $category->getTopics()->toArray();
      foreach ($topics as $topic) {
        $topic->setDeleteFlag($this->getParameter('delete_flag.deleted'));
      }
      /* $this->entityManager->remove($category); */
      $category->setDeleteFlag($this->getParameter('delete_flag.deleted'));

      $this->entityManager->persist($category);
      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  public function updateCategory ($params)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();
      $category = $this->entityManager->getRepository(Category::class)->find($params['cat_id']);
      $category->setName($params['cat_name']);
      $category->setSlug($this->nameToSlug(trim($params['cat_name'])));
      $category->setDescription($params['cat_desc']);
      $violations = $this->validator->validate($category);
      if ($violations && count($violations) > 0) {
        $messages = [];
        foreach ($violations as $key => $violation) {
          $messages[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $messages;
      }

      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  /* create slug from category name */
  public function nameToSlug($categoryName)
  {
    $categoryName = trim(mb_strtolower($categoryName));
    $categoryName = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $categoryName);
    $categoryName = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $categoryName);
    $categoryName = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $categoryName);
    $categoryName = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $categoryName);
    $categoryName = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $categoryName);
    $categoryName = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $categoryName);
    $categoryName = preg_replace('/(đ)/', 'd', $categoryName);
    $categoryName = preg_replace('/[^a-z0-9-\s]/', '', $categoryName);
    $categoryName = preg_replace('/([\s]+)/', '-', $categoryName);
    return $categoryName;
  }

}
