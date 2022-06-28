<?php

namespace App\Services\Admin;
use App\ServiceInterfaces\Admin\SubscriberServiceInterface;
use App\Entity\Subscriber;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Carbon\Carbon;

class SubscriberService extends AbstractController implements SubscriberServiceInterface 
{

    private $subscriberRepository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SubscriberRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllSubscriberByQueryBuilder()
    {
        $queryBuilder = $this->subscriberRepository->createQueryBuilder('subscriber')
            ->orderBy('subscriber.id', 'DESC')
            ->getQuery();
        return $queryBuilder;
    }

    public function getAllSubscribers()
    {
        $subscribers = $this->entityManager->getRepository(Subscriber::class)->findAll();
        return $subscribers;
    }

    public function getAllSubscriberEmails()
    {
        $subscriberEmails = $this->subscriberRepository->createQueryBuilder('subscriber')
            ->select('subscriber.email')
            ->getQuery()
            ->getSingleColumnResult();
        return $subscriberEmails;
    }

    public function countTotalSubscribers()
    {
        $totalSubscribers = $this->subscriberRepository->createQueryBuilder("subscriber")
            ->select("count(subscriber.id)")
            ->getQuery()
            ->getSingleScalarResult(); //use this function to get only scalar result 
        return $totalSubscribers;
    }

    public function countTodaySubscribers()
    {
        $today = Carbon::today()->setTimeZone($this->getParameter('timezone'))->toDateString();
  
        $todaySubscribers = $this->subscriberRepository->createQueryBuilder("subscriber")
            ->select("count(subscriber.id)")
            ->where("subscriber.created_at like :today ")
            ->setParameter("today", $today.'%')
            ->getQuery()
            ->getSingleScalarResult();
        return $todaySubscribers;
    }

    public function getSubscriberById($id)
    {
        try {
            $subscriber = $this->entityManager->getRepository(Subscriber::class)->find($id);

            return $subscriber;
        } catch (\Exception $e) {
            return false;
        }
    }

    /* Update subscribed status to 1 after sending thank for subscription email */

    public function updateSubscribedStatus($id)
    {
        $subscriber = $this->entityManager->getRepository(Subscriber::class)->find($id);

        $subscriber->setSubscribed($this->getParameter('email_subscribed'));

        $this->entityManager->flush();
    }
    
}