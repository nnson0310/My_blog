<?php

namespace App\Controller\Admin;

use App\Middleware\LoginAuthenticated;
use App\Services\Admin\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Services\Admin\BlogService;
use App\Services\Admin\CommentService;
use App\Services\Admin\SubscriberService;

class DashboardController extends AbstractController implements LoginAuthenticated
{

  protected $requestStack;

  protected $adminService;

  protected $blogService;

  protected $commentServicel;

  protected $subscriberService;

  protected $paginatorInterface;

  public function __construct(
    RequestStack $requestStack,
    AdminService $adminService,
    BlogService $blogService,
    CommentService $commentService,
    SubscriberService $subscriberService
  ) {
    $this->requestStack = $requestStack;
    $this->adminService = $adminService;
    $this->commentService = $commentService;
    $this->blogService = $blogService;
    $this->subscriberService = $subscriberService;
  }

  /**
   * @Route("/backdoor/dashboard", name="admin_dashboard", methods={"GET", "HEAD"})
   */
  public function dashboard(Request $request): Response
  {
    $session = $this->requestStack->getSession();
    $username = $session->get('username');
    if ($username) {

      //get total
      $totalPosts = $this->blogService->countTotalBlogs();
      $totalComments = $this->commentService->countTotalComments();
      $totalSubscribers = $this->subscriberService->countTotalSubscribers();
      $totalViews = $this->blogService->countTotalViews();

      //get today's total
      $todayPosts = $this->blogService->countTodayBlogs();
      $todayComments = $this->commentService->countTodayComments();
      $todaySubscribers = $this->subscriberService->countTodaySubscribers();
      $todayViews = $this->blogService->countTodayViews();

      return $this->render('admin/dashboard/index.html.twig', [
        'totalPosts' => $totalPosts,
        'totalComments' => $totalComments,
        'totalSubscribers' => $totalSubscribers,
        'totalViews' => $totalViews,
        'todayPosts' => $todayPosts,
        'todayComments' => $todayComments,
        'todaySubscribers' => $todaySubscribers,
        'todayViews' => $todayViews
      ]);
    }
    return $this->redirectToRoute('admin_auth_login_form');
  }

  public function recentUser(): Response
  {
    $session = $this->requestStack->getSession();
    $username = $session->get('username');
    $admin = $this->adminService->getAdminByUsername($username);
    return $this->render('admin/dashboard/components/_recent_user.html.twig', ['username' => $username, 'admin' => $admin]);
  }
}
