<?php

namespace App\Services\Admin;

use App\ServiceInterfaces\Admin\TopicServiceInterface;
use App\Entity\Topic;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TopicService extends AbstractController implements TopicServiceInterface
{

  protected $entityManager;

  protected $topicRepository;

  protected $categoryRepository;

  protected $validator;

  public function __construct(EntityManagerInterface $entityManager, TopicRepository $topicRepository, CategoryRepository $categoryRepository, ValidatorInterface $validator)
  {
    $this->entityManager = $entityManager;
    $this->topicRepository = $topicRepository;
    $this->categoryRepository = $categoryRepository;
    $this->validator = $validator;
  }

  public function getAllTopic()
  {
    $queryBuilder  = $this->topicRepository->createQueryBuilder('topic')
      ->where('topic.delete_flag is NULL')
      ->orderBy('topic.id', 'DESC')
      ->getQuery();
    return $queryBuilder;
  }

  public function getAllDeletedTopic()
  {
    $queryBuilder  = $this->topicRepository->createQueryBuilder('topic')
      ->where('topic.delete_flag is NOT NULL')
      ->orderBy('topic.id', 'DESC')
      ->getQuery();
    return $queryBuilder;
  }

  public function restoreTopic ($params)
    {
      try {
        $this->entityManager->getConnection()->beginTransaction();
        $topic = $this->entityManager->getRepository(Topic::class)->find($params);

        $topic->setDeleteFlag(null);
        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();
        return true;
      } catch (\Exception $e) {
        $this->entityManager->getConnection()->rollBack();
        return false;
      }

    }

  public function createNewTopic($params)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();

      $category = $this->categoryRepository->find($params['topic_cat']);

      $topic = new Topic();
      $topic->setName($params['topic_name']);
      $topic->setSlug($this->nameToSlug($params['topic_name']));
      $topic->setDescription($params['topic_desc']);
      $topic->setCategory($category);

      $violations = $this->validator->validate($topic);

      if ($violations && count($violations) > 0) {
        $messages = [];
        foreach ($violations as $violation) {
          $messages[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $messages;
      }

      $this->entityManager->persist($topic);
      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  public function deleteTopic($params)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();

      $topic = $this->entityManager->getRepository(Topic::class)->find($params['topic_id']);
      $topic->setDeleteFlag($this->getParameter('delete_flag.deleted'));

      $this->entityManager->flush();
      $this->entityManager->getConnection()->commit();
      return true;
    } catch (\Exception $e) {
      $this->entityManager->getConnection()->rollBack();
      return false;
    }
  }

  public function updateTopic($params)
  {
    try {
      $this->entityManager->getConnection()->beginTransaction();

      $topic = $this->entityManager->getRepository(Topic::class)->find($params['topic_id']);
      $category = $this->categoryRepository->find($params['topic_cat']);

      $topic->setName($params['topic_name']);
      $topic->setSlug($this->nameToSlug($params['topic_name']));
      $topic->setDescription($params['topic_desc']);
      $topic->setCategory($category);

      $violations = $this->validator->validate($topic);
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

  /* create slug from topic name */
  public function nameToSlug($topicName)
  {
    $topicName = trim(mb_strtolower($topicName));
    $topicName = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $topicName);
    $topicName = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $topicName);
    $topicName = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $topicName);
    $topicName = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $topicName);
    $topicName = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $topicName);
    $topicName = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $topicName);
    $topicName = preg_replace('/(đ)/', 'd', $topicName);
    $topicName = preg_replace('/[^a-z0-9-\s]/', '', $topicName);
    $topicName = preg_replace('/([\s]+)/', '-', $topicName);
    return $topicName;
  }
}
