<?php

namespace App\Services\Api;

use App\Entity\Message;
use App\ServiceInterfaces\Api\MessageServiceInterface;
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

    public function storeMessage($data)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();

            $message = new Message();
            $message->setSubject($data['subject']);
            $message->setContent($data['content']);
            $message->setEmail($data['emailAddress']);
            $message->setSender($data['firstName'].' '.$data['lastName']);

            $this->entityManager->persist($message);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return false;
        }
    }
}