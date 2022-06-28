<?php

namespace App\Services\Admin;

use App\Entity\Category;
use App\Entity\Topic;
use App\Entity\Tags;
use App\Repository\CategoryRepository;
use App\ServiceInterfaces\Admin\CsvServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CsvService implements CsvServiceInterface
{

    protected $entityManager;

    protected $validator;

    protected $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategoryFromCsv($params)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            $category = new Category();
            $category->setName($params['name']);
            $category->setDescription($params['description']);
            $violations = $this->validator->validate($category);
            if ($violations && count($violations) > 0) {
                return "Import fails! Maybe there is something wrong with csv. Please check csv rules for more details.";
            }
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
        }
    }

    public function createTopicFromCsv($params)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();

            $category = $this->categoryRepository->findOneBy(['name' => $params['category']]);
            if (!is_null($category)) {
                $topic = new Topic();
                $topic->setName($params['name']);
                $topic->setDescription($params['description']);
                $topic->setCategory($category);

                $violations = $this->validator->validate($topic);

                if ($violations && count($violations) > 0) {
                    return "Import fails! Maybe there is something wrong with csv. Please check csv rules for more details.";
                }

                $this->entityManager->persist($topic);
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
                return true;
            }
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
        }
    }

    public function createTagsFromCsv($params)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            $tags = new Tags();
            $tags->setName($params['name']);
            $violations = $this->validator->validate($tags);
            if ($violations && count($violations) > 0) {
                return "Import fails! Maybe there is something wrong with csv. Please check csv rules for more details.";
            }
            $this->entityManager->persist($tags);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
        }
    }
}
