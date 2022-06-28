<?php

namespace App\Controller;

use App\Services\Admin\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

/**
* @Route("/mail", name="mail_")
*/
class MailerController extends AbstractController
{

    protected $mailerService;

    protected $requestStack;

    public function __construct(MailerService $mailerService, RequestStack $requestStack)
    {
        $this->mailerService = $mailerService;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/register/confirmation", name="register_confirmation")
     */
    public function registerConfirmation (): Response
    {
        $session = $this->requestStack->getSession();
        $email = $session->get('userEmail');
        $id = $session->get('userId');
        if (isset($email) && isset($id)) {
            $this->mailerService->registerConfirmation($email, $id);
        }
        return $this->redirectToRoute('admin_auth_register_success');   
    }
}
