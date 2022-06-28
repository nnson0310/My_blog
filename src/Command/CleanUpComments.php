<?php

namespace App\Command;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Carbon\Carbon;
use Symfony\Component\Console\Input\InputArgument;

class CleanUpComments extends Command
{
    private $entityManager;

    protected static $defaultName = 'app:clean-up:comments';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure():void
    {
        $this->setHelp("This command help you cleaning up all comments which is not approved in 14 days");
    }

    protected function execute(InputInterface $input, OutputInterface $output, int $deadline = 14, string $timezone = 'Asia/Bangkok'):int
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            $comments = $this->entityManager->getRepository(Comment::class)->findBy([
                "approval" => 0
            ]);
            $today = strtotime(Carbon::today()->setTimeZone($timezone)->toDateString());
            foreach($comments as $comment) {
                $createdAt = strtotime($comment->getCreatedAt()->format('Y-m-d'));
                $term = ($today - $createdAt)/86400;
                if ($term >= $deadline) {
                    $this->entityManager->remove($comment);
                }
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            $output->writeln('Cleaning up all unapproved comments successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            $output->writeln('Can not clean up comments!');
            return Command::FAILURE;
        }
    }
}