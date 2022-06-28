<?php

namespace App\Services\Api;

use App\Entity\Tags;
use App\ServiceInterfaces\Api\TagsServiceInterface;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TagsService extends AbstractController implements TagsServiceInterface
{

  protected $tagsRepository;

  protected $paginator;

  protected $entityManager;

  protected $validator;

  public function __construct(
      PaginatorInterface $paginator, 
      EntityManagerInterface $entityManager, 
      TagsRepository $tagsRepository
    )
  {
    $this->tagsRepository = $tagsRepository;
    $this->paginator = $paginator;
    $this->entityManager = $entityManager;
  }

  public function getAllTags()
  {
    $tags = $this->tagsRepository->createQueryBuilder('tags')
        ->select('tags.id, tags.name, tags.slug')
        ->where('tags.delete_flag is NULL')
        ->getQuery()
        ->getArrayResult();
    return $tags;
  }

}
