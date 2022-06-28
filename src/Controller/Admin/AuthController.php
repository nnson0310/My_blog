<?php

namespace App\Controller\Admin;

use App\Middleware\LogoutAuthenticated;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Admin\AdminService;
use App\Services\Admin\MailerService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/backdoor/auth", name="admin_auth_")
 */
class AuthController extends AbstractController
{
    protected $adminService;

    protected $requestStack;

    protected $mailerService;

    protected $passwordHasher;

    public function __construct(AdminService $adminService, RequestStack $requestStack, MailerService $mailerService, UserPasswordHasherInterface $passwordHasher)
    {
        $this->adminService = $adminService;
        $this->requestStack = $requestStack;
        $this->mailerService = $mailerService;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @Route("/register/form", name="register_form", methods={"GET", "HEAD"})
     */
    public function registerForm(): Response
    {
        $session = $this->requestStack->getSession();
        if ($session->get('username')) {
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('admin/auth/register_form.html.twig');
    }

    /**
     * @Route("/register/success", name="register_success", methods={"GET", "HEAD"})
     */
    public function registerSuccess(Request $request): Response
    {
        return $this->render('admin/auth/register_success.html.twig');
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request): Response
    {
        $_token = $request->request->get('_token');
        $registrationCode = $request->request->get('registration_code');
        if ($this->isCsrfTokenValid('register', $_token) && $registrationCode === $this->getParameter('registration_code')) {
            $params = $request->request->all();
            $result = $this->adminService->createAdminAccount($params);
            if (is_array($result)) {
                foreach ($result as $key => $value) {
                    if ($key == 'username') {
                        $this->addFlash('username', $value);
                    }
                    if ($key == 'password') {
                        $this->addFlash('password', $value);
                    }
                    if ($key == 'email') {
                        $this->addFlash('email', $value);
                    }
                }
                return $this->redirectToRoute('admin_auth_register_form');
            }
            $session = $this->requestStack->getSession();
            if ($result) {
                $session->set('userEmail', $params['email']);
                $admin = $this->adminService->getAdminByUsername($params['username']);
                $userId= $admin->getId();
                $session->set('userId', $userId);
                $this->mailerService->registerConfirmation($params['email'], $userId);
                return $this->redirectToRoute('admin_auth_register_success');
            }
            $session->set('msg', 'Fail to create admin account. Please try again later!');
            return $this->redirectToRoute('admin_auth_register_form');
        }
        return new Response('Operation not allowed or Registration code mismatch', Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/login/form", name="login_form", methods={"GET", "HEAD"})
     */
    public function loginForm(): Response
    {
        $session = $this->requestStack->getSession();
        if ($session->get('username')) {
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('admin/auth/login_form.html.twig');
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request): Response
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('login', $_token)) {
            $params = $request->request->all();
            $admin = $this->adminService->getAdminByAccount($params);
            if ($admin) {
                if ($this->passwordHasher->isPasswordValid($admin, $params['password'])) {
                    if ($admin->getEmailConfirmation() == $this->getParameter('email_confirmation.confirmed')) {
                        $session = new Session();
                        $session->set('username', $admin->getUsername());
                        return $this->redirectToRoute('admin_dashboard');
                    }
                    return $this->redirectToRoute('admin_auth_register_success');
                }
                $this->addFlash("password", "Password is invalid. Please try again.");
                return $this->redirectToRoute('admin_auth_login_form');
            }
            $this->addFlash("account", "This username or email does not belong to any account. Please try again.");
            return $this->redirectToRoute('admin_auth_login_form');
        }
        return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST, ['content-type' => 'text/plain']);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET", "HEAD"})
     */
    public function logout(): Response
    {
        $session = $this->requestStack->getSession();
        $session->clear();
        return $this->redirectToRoute('admin_auth_login_form');
    }

    /**
     * @Route("/account/verify/{user}", name="verify_account", methods={"GET", "HEAD"})
     */
    public function verifyAccount ($user)
    {
        $result = $this->adminService->verifyAccount($user);
        if ($result) {
            return $this->render('admin/auth/verify_success.html.twig');
        }
        return new Response('Unable to verify email due to some internal server error. Contact us for more information.', Response::HTTP_INTERNAL_SERVER_ERROR, ['Content-type' => 'text/plain']);
    }

}
