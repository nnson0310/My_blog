<?php

namespace App\Controller\Api;

use App\Services\Api\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @Route("/api/comment/post", name="api_comment_post")
     */
    public function postComment(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->commentService->storeComment($data);
        if ($result) {
            return new JsonResponse([
                'msg' => 'Thank you so much for leaving comments. Your comment is waiting for approval. Please be patient!'
            ], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        }
        return new JsonResponse([
            'msg' => 'Comments can not be posted due to some server error. We really appreciate if you can inform us via email or sending message.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/api/post_details/comments/get/{id}", name="api_post_details_comment_get")
     */
    public function getComments($id): JsonResponse
    {
        $comments = $this->commentService->getCommentsByPost($id);

        if ($comments){
            return new JsonResponse([
                'comment' => $comments
            ], JsonResponse::HTTP_OK, ['Content-type' => 'application/json']);
        }
        
        return new JsonResponse([
            'noComment' => 'There are no comments yet. Be the first one.'
        ], JsonResponse::HTTP_OK, ['Content-type' => 'application/json']);
    }

}
