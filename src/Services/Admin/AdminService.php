<?php

namespace App\Services\Admin;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\ServiceInterfaces\Admin\AdminServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminService extends AbstractController implements AdminServiceInterface
{

    protected $entityManager;

    protected $adminRepository;

    protected $validator;

    protected $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        AdminRepository $adminRepository,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->adminRepository = $adminRepository;
        $this->validator = $validator;
        $this->passwordHasher = $passwordHasher;
    }


    public function createAdminAccount($params)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            $admin = new Admin();
            $admin->setUsername(trim($params['username']));
            $admin->setPassword(trim($params['password']));
            $admin->setEmail(trim($params['email']));
            $admin->setRole($this->getParameter('role.admin'));

            $violations = $this->validator->validate($admin);

            if ($violations && count($violations) > 0) {
                $messages = [];
                foreach ($violations as $violation) {
                    $messages[$violation->getPropertyPath()] = $violation->getMessage();
                }
                return $messages;
            }

            $admin->setPassword($this->passwordHasher->hashPassword($admin, $params['password']));

            $this->entityManager->persist($admin);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
        }
    }

    public function getAdminByAccount($params)
    {
        try {
            $admin = $this->adminRepository->findAdminByAccount($params['account']);
            return $admin;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAdminByUsername($username)
    {
        try {
            $admin = $this->adminRepository->findAdminByAccount($username);
            return $admin;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyAccount($id)
    {
        try {
           $this->entityManager->getConnection()->beginTransaction();
           $admin = $this->entityManager->getRepository(Admin::class)->find($id);
           $admin->setEmailConfirmation($this->getParameter('email_confirmation.confirmed'));

           $this->entityManager->flush();
           $this->entityManager->getConnection()->commit();
           return true;
        } catch (\Exception $e) {
           $this->entityManager->getConnection()->rollBack();
           return false;
        }
    }

    public function updateAdminAccount($username, $params)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            $admin = $this->adminRepository->findAdminByAccount($username);
            $admin->setUsername(trim($params['username']));
            $admin->setEmail(trim($params['email']));
            $admin->setAvatar($params['avatar']);
            if ($params['password'] == '' || empty($params['password'])) {
                $violations = $this->validator->validate($admin, null, ['update_info']);
                if ($violations && count($violations) > 0) {
                    $messages = [];
                    foreach ($violations as $violation) {
                        $messages[$violation->getPropertyPath()] = $violation->getMessage();
                    }
                    return $messages;
                }
            } else {
                $admin->setPassword($params['password']);
                $violations = $this->validator->validate($admin);
                dd($violations);
                if ($violations && count($violations) > 0) {
                    $messages = [];
                    foreach ($violations as $violation) {
                        $messages[$violation->getPropertyPath()] = $violation->getMessage();
                    }
                    return $messages;
                }
                $admin->setPassword($this->passwordHasher->hashPassword($admin, $params['password']));
            }

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            return false;
        }
    }
}
