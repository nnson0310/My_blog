<?php

namespace App\Controller\Admin;

use App\Services\Admin\CommentService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/backdoor/comment", name="admin_comment_")
 */
class CommentController extends AbstractController
{
    protected $requestStack;

    protected $commentService;

    protected $paginator;

    public function __construct (RequestStack $requestStack, CommentService $commentService, PaginatorInterface $paginator)
    {
      $this->requestStack = $requestStack;
      $this->commentService = $commentService;
      $this->paginator = $paginator;
    }

    /**
     * @Route("/index", name="index", methods={"GET", "HEAD"})
     */
    public function index (Request $request): Response
    {
      $queryBuilder = $this->commentService->getAllCommentsByQueryBuilder();
      $domain = $this->getParameter('domain').'/single_post/';
      $comments = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
      return $this->render('/admin/dashboard/comment/index.html.twig', ['comments' => $comments, 'domain' => $domain]);
    }

    /**
     * @Route("/approval", name="approval", methods={"POST"})
     */
    public function approvalComment (Request $request): JsonResponse
    {
      $commentId = $request->request->get('id');
      $result = $this->commentService->approvalComment($commentId);
      if ($result) {
        return new JsonResponse(['msg' => 'Approve comment successfully'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
      }
      return new JsonResponse(['msg' => 'Comment can not be approved due to some errors. Please try again later.'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/reject", name="reject", methods={"POST"})
     */
    public function rejectComment (Request $request): JsonResponse
    {
      $commentId = $request->request->get('id');
      $result = $this->commentService->rejectComment($commentId);
      if ($result) {
        return new JsonResponse(['msg' => 'Reject comment successfully'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
      }
      return new JsonResponse(['msg' => 'Comment can not be rejected due to some errors. Please try again later.'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
