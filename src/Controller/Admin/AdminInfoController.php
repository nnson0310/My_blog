<?php

namespace App\Controller\Admin;

use App\Services\Admin\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Route("/backdoor/info", name="admin_info_")
 */
class AdminInfoController extends AbstractController
{

    protected $requestStack;

    protected $adminService;

    protected $fileSystem;

    public function __construct(RequestStack $requestStack, AdminService $adminService, Filesystem $fileSystem)
    {
        $this->requestStack = $requestStack;
        $this->adminService = $adminService;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @Route("/details", name="details")
     */
    public function adminInfoDetails(): Response
    {
        $session = $this->requestStack->getSession();
        $username = $session->get('username');
        $admin = $this->adminService->getAdminByUsername($username);
        if ($admin) {
            return $this->render('admin/dashboard/admin_info/details.html.twig', ['admin' => $admin]);
        }
        return $this->redirectToRoute('admin_dashboard');
    }

    /**
     * @Route("/update", name="update")
     */
    public function updateAdminInfo(Request $request): Response
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('update_admin_info', $_token)) {
            $params = $request->request->all();
            $file = $request->files->get('avatar');
            $username = $this->requestStack->getSession()->get('username');
            $admin = $this->adminService->getAdminByUsername($username);
            if ($admin) {
                if ($file) {
                    $dir = $request->server->get('DOCUMENT_ROOT') . '/assets/files/admin_info';
                    //remove old image if exists
                    if ($admin->getAvatar() && $this->fileSystem->exists($dir.'/'.$admin->getAvatar())) {
                        $this->fileSystem->remove($dir.'/'.$admin->getAvatar());
                    }
                    $name = explode(".", $file->getClientOriginalName());
                    $fileName = $name[0].'.'.random_int(0, 1000).'.'.$name[1];
                    $params['avatar'] = $fileName;
                    $file->move($dir, $fileName);
                }
                $result = $this->adminService->updateAdminAccount($username, $params);
                if (is_array($result)) {
                    foreach ($result as $key => $value) {
                        if ($key == 'username') {
                            $this->addFlash('username', $value);
                        }
                        if ($key == 'password') {
                            $this->addFlash('password', $value);
                        }
                        if ($key == 'email') {
                            $this->addFlash('email', $value);
                        }
                        return $this->redirectToRoute('admin_info_details');
                    }
                }
                else if (is_string($result)) {
                    $this->addFlash('msg', $result);
                    return $this->redirectToRoute('admin_info_details');
                }
                $this->addFlash('msg', 'Update admin account successfully.');
                return $this->redirectToRoute('admin_info_details');
            }
            $this->addFlash('msg', 'Admin account does not exist.');
            return $this->redirectToRoute('admin_info_details');
        }
        return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST, ['content-type' => 'text/plain']);
    }
}
