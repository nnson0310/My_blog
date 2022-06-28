<?php

namespace App\Services\Api;

use App\Entity\Category;
use App\ServiceInterfaces\Api\CategoryServiceInterface;
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

  public function getAllCategories()
  {
    $categories = $this->categoryRepository->getAllCategoryForApi();

    return $categories;
  }


}
