<?php

namespace App\Services\Api;

use App\ServiceInterfaces\Api\TopicServiceInterface;
use App\Entity\Topic;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicService extends AbstractController implements TopicServiceInterface 
{

    private $topicRepository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllTopics()
    {
        $topics = $this->topicRepository->createQueryBuilder('topic')
            ->select('topic.name', 'topic.description')
            ->where('topic.delete_flag is NULL')
            ->orderBy('topic.id', 'DESC')
            ->getQuery()
            ->getArrayResult();
        return $topics;
    }

}