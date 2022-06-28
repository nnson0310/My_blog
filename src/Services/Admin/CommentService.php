<?php

namespace App\Services\Admin;

use App\ServiceInterfaces\Admin\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CommentRepository;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Carbon\Carbon;

class CommentService extends AbstractController implements CommentServiceInterface
{

    protected $commentRepository;

    protected $entityManager;

    public function __construct(CommentRepository $commentRepository, EntityManagerInterface $entityManager)
    {
        $this->commentRepository = $commentRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllCommentsByQueryBuilder()
    {
        $queryBuilder = $this->commentRepository->createQueryBuilder('comment')
            ->where('comment.delete_flag is NULL')
            ->orderBy('comment.id', 'DESC')
            ->getQuery();
        return $queryBuilder;
    }

    public function countTotalComments()
    {
        $totalComments = $this->commentRepository->createQueryBuilder("comment")
            ->select("count(comment.id)")
            ->getQuery()
            ->getSingleScalarResult(); //use this function to get only scalar result 
        return $totalComments;
    }

    public function countTodayComments()
    {
        $today = Carbon::today()->setTimeZone($this->getParameter('timezone'))->toDateString();
  
        $todayComments = $this->commentRepository->createQueryBuilder("comment")
            ->select("count(comment.id)")
            ->where("comment.created_at like :today ")
            ->setParameter("today", $today.'%')
            ->getQuery()
            ->getSingleScalarResult();
        return $todayComments;

    }

    public function approvalComment($id) 
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            $comment = $this->entityManager->getRepository(Comment::class)->find($id);

            $comment->setApproval($this->getParameter('comment_approval'));
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
        }
    }

    public function rejectComment($id) 
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            $comment = $this->entityManager->getRepository(Comment::class)->find($id);

            $comment->setApproval($this->getParameter('comment_reject'));
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
        }
    }
}
