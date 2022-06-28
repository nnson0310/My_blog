<?php

namespace App\Command;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ResetTodayViews extends Command
{
    
    private $entityManager;

    protected static $defaultName = 'app:reset:today-views';

    protected static $defaultDescription = 'Reset today views of each blog after a day';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp("This command help you to reset today views of each blog for summarizing today views.");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();

            $blogs = $this->entityManager->getRepository(Blog::class)->findAll();
            foreach($blogs as $blog) {
                $blog->setTodayViews(0);
                $this->entityManager->persist($blog);
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            $output->writeln('Reset all today views count successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            $output->writeln('Can not reset today views count!');
            return Command::FAILURE;
        }
    }
}
