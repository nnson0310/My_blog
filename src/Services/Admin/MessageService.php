<?php

namespace App\Services\Admin;

use App\Entity\Message;
use App\ServiceInterfaces\Admin\MessageServiceInterface;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

class MessageService implements MessageServiceInterface
{
    protected $messageRepository;

    protected $entityManager;

    public function __construct(MessageRepository $messageRepository, EntityManagerInterface $entityManager)
    {
        $this->messageRepository = $messageRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllMessages() 
    {
        return $this->messageRepository->createQueryBuilder('message')
            ->orderBy('message.id', 'DESC')
            ->getQuery();
    }
}