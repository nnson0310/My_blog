<?php

namespace App\Services\Api;
use App\ServiceInterfaces\Api\SubscribeServiceInterface;
use App\Entity\Subscriber;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriberService extends AbstractController implements SubscribeServiceInterface 
{

    private $subscriberRepository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SubscriberRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
        $this->entityManager = $entityManager;
    }

    public function storeSubscribedEmail($data)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();

            $subscriber = $this->entityManager->getRepository(Subscriber::class)->findOneBy([
                'email' => $data['email']
            ]);
            if ($subscriber) {
                return "You have already subscribed!";
            }
            $subscriber = new Subscriber();
            $subscriber->setEmail($data['email']);
            $subscriber->setSubscribed($this->getParameter('email_unsubscribed'));

            $this->entityManager->persist($subscriber);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
        }
    }
}