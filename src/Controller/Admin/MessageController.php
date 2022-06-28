<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Admin\MessageService;
use Knp\Component\Pager\PaginatorInterface;

/**
* @Route("/backdoor/message", name="admin_message_")
*/
class MessageController extends AbstractController
{

    protected $messageService;

    protected $paginator;

    public function __construct(MessageService $messageService, PaginatorInterface $paginator)
    {
        $this->messageService = $messageService;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request): Response
    {
        $queryBuilder = $this->messageService->getAllMessages();
        $messages = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
        return $this->render('admin/dashboard/messages/index.html.twig', [
            'messages' => $messages
        ]);
    }

}
