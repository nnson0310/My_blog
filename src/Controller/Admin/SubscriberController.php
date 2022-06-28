<?php

namespace App\Controller\Admin;

use App\Services\Admin\SubscriberService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Services\Admin\MailerService;

/**
 * @Route("/backdoor/subscriber", name="admin_subscriber_")
 */
class SubscriberController extends AbstractController
{

    protected $requestStack;

    protected $subscriberService;

    protected $paginator;

    protected $mailerService;

    public function __construct (RequestStack $requestStack, SubscriberService $subscriberService, PaginatorInterface $paginator, MailerService $mailerService)
    {
      $this->requestStack = $requestStack;
      $this->subscriberService = $subscriberService;
      $this->paginator = $paginator;
      $this->mailerService = $mailerService;
    }

    /**
     * @Route("/index", name="index", methods={"GET", "HEAD"})
     */
    public function index (Request $request): Response
    {
        $queryBuilder = $this->subscriberService->getAllSubscriberByQueryBuilder();
        $subscribers = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
        return $this->render('/admin/dashboard/subscriber/index.html.twig', [
            'subscribers' => $subscribers
        ]);
    }

    /**
     * @Route("/subscription_email/send", name="send_subscription_email", methods={"POSt"})
     */
    public function sendSubscriptionEmail (Request $request): Response
    {
        $_token = $request->request->get("_token");
        if ($this->isCsrfTokenValid('send_subscription_email', $_token)) {
            $subscriberId = $request->request->get('subscriber_id');
            $subscriberInfo = $this->subscriberService->getSubscriberById($subscriberId);
            if ($subscriberInfo) {
                //send subscription email to subscribed user
                $this->mailerService->thankForSubscription($subscriberInfo);
                $this->subscriberService->updateSubscribedStatus($subscriberInfo->getId());
                $this->addFlash("msg", "Sending subscription confirmation email successfully.");
                return $this->redirectToRoute('admin_subscriber_index');
            }
            $this->addFlash("msg", "This subscribed email does not exist. Please check again");
            return $this->redirectToRoute('admin_subscriber_index');

        }
        return new Response('Operation not allowed or Registration code mismatch', Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

}
