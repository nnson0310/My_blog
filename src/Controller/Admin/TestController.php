<?php

namespace App\Controller\Admin;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use App\Entity\Blog;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    private $blogRepository;
    
    private $commentRepository;

    private $entityManager;

    public function __construct(
        BlogRepository $blogRepository, 
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->blogRepository = $blogRepository;
        $this->commentRepository = $commentRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/backdoor/api/test", name="api_test", methods={"GET", "HEAD"})
     */
    public function test(Request $request)
    {
        $comments = $this->entityManager->getRepository(Comment::class)->findBy([
            "approval" => 0,
            "delete_flag" => null
        ]);
        $today = strtotime(Carbon::today()->setTimeZone($this->getParameter("timezone"))->toDateString());
        foreach($comments as $comment) {
            $createdAt = strtotime($comment->getCreatedAt()->format('Y-m-d'));
            dd(($today - $createdAt)/86400);   
        }     
        return new Response('Success', Response::HTTP_OK, ['Content-type' => 'text/plain']);
    }
}