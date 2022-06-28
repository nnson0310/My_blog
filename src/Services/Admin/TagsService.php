<?php

namespace App\Services\Admin;

use App\ServiceInterfaces\Admin\TagsServiceInterface;
use App\Repository\TagsRepository;
use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Code\Generator\DocBlock\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TagsService extends AbstractController implements TagsServiceInterface
{

    protected $tagsRepository;

    protected $entityManager;

    protected $validator;

    public function __construct(TagsRepository $tagsRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->tagsRepository = $tagsRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function getAllTags ()
    {
        return $this->tagsRepository->createQueryBuilder('tags')
        ->where('tags.delete_flag is NULL')
        ->orderBy('tags.id', 'DESC')
        ->getQuery();
    }

    public function getAllDeletedTags ()
    {
      return $this->tagsRepository->createQueryBuilder('tags')
        ->where('tags.delete_flag is NOT NULL')
        ->orderBy('tags.id', 'DESC')
        ->getQuery();
    }

    public function restoreTags ($params)
    {
      try {
        $this->entityManager->getConnection()->beginTransaction();
        $tags = $this->entityManager->getRepository(Tags::class)->find($params);

        $tags->setDeleteFlag(null);
        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();
        return true;
      } catch (\Exception $e) {
        $this->entityManager->getConnection()->rollBack();
        return false;
      }

    }

    public function createNewTags($params)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            $tags = new Tags();
            $tags->setName(trim($params));
            $tags->setSlug($this->nameToSlug(trim($params)));

            $violations = $this->validator->validate($tags);

            if ($violations && count($violations) > 0) {
              $messages = [];
              foreach ($violations as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
              }
              return $messages;
            }

            $this->entityManager->persist($tags);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
          } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
          }
    }

    public function deleteTags($params)
    {
      try {
        $this->entityManager->getConnection()->beginTransaction();

        $tags = $this->entityManager->getRepository(Tags::class)->find($params);
        $tags->setDeleteFlag($this->getParameter('delete_flag.deleted'));

        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();
        return true;
      } catch (\Exception $e) {
        $this->entityManager->getConnection()->rollBack();
        return false;
      }
    }

    public function updateTags($params)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();
      $tags = $this->entityManager->getRepository(Tags::class)->find($params['tags_id']);
      $tags->setName($params['tags_name']);
      $tags->setSlug($this->nameToSlug($params['tags_name']));

      $violations = $this->validator->validate($tags);
      if ($violations && count($violations) > 0) {
        $messages = [];
        foreach ($violations as $violation) {
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

  /* create slug from tag name */
  public function nameToSlug($tagName)
  {
    $tagName = trim(mb_strtolower($tagName));
    $tagName = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $tagName);
    $tagName = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $tagName);
    $tagName = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $tagName);
    $tagName = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $tagName);
    $tagName = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $tagName);
    $tagName = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $tagName);
    $tagName = preg_replace('/(đ)/', 'd', $tagName);
    $tagName = preg_replace('/[^a-z0-9-\s]/', '', $tagName);
    $tagName = preg_replace('/([\s]+)/', '-', $tagName);
    return $tagName;
  }

}
