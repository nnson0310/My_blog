<?php

namespace App\Services\Api;

use App\ServiceInterfaces\Api\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CommentRepository;
use App\Repository\BlogRepository;
use App\Entity\Comment;
use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;

class CommentService extends AbstractController implements CommentServiceInterface 
{

    protected $commentRepository;

    protected $blogRepository;

    protected $entityManager;

    public function __construct(CommentRepository $commentRepository, EntityManagerInterface $entityManager, BlogRepository $blogRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->entityManager = $entityManager;
        $this->blogRepository = $blogRepository;
    }

    public function storeComment($data)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();

            $comment = new Comment();
            $comment->setName($data['name']);
            $comment->setEmail($data['email']);
            $comment->setWebsite($data['website']);
            $comment->setContent(strip_tags($data['comment']));
            $comment->setApproval($this->getParameter('comment_reject'));
            
            //set blog for comment
            $blog = $this->entityManager->getRepository(Blog::class)->find($data['blogId']);
            $comment->setBlog($blog);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    //get comment due to each post
    public function getCommentsByPost($id)
    {
        $comments = $this->entityManager->createQuery(
            'SELECT c.name, c.content, c.comment_parent_id, c.updated_at, c.approval
            FROM App\Entity\Comment c
            INNER JOIN c.blog b
            WHERE b.id = :id')->setParameter('id', $id);

        return $comments->getResult();
    }

}