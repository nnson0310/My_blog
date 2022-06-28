<?php

namespace App\Controller\Admin;

use App\Services\Admin\CategoryService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Route("/backdoor/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{

    protected $requestStack;

    protected $categoryService;

    protected $paginator;

    public function __construct (RequestStack $requestStack, CategoryService $categoryService, PaginatorInterface $paginator)
    {
      $this->requestStack = $requestStack;
      $this->categoryService = $categoryService;
      $this->paginator = $paginator;
    }

    /**
     * @Route("/index", name="index", methods={"GET", "HEAD"})
     */
    public function index (Request $request): Response
    {
        $queryBuilder = $this->categoryService->getAllCategoryByQueryBuilder();
        $categories = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
        return $this->render('/admin/dashboard/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/update", name="update", methods={"POST"})
     */
    public function updateCategory (Request $request): Response
    {
        $_token = $request->request->get("_token");
        if ($this->isCsrfTokenValid('update_category', $_token)) {
            $params = $request->request->all();
            $result = $this->categoryService->updateCategory($params);
            if (is_array($result)) {
                $session = $this->requestStack->getSession();
                $session->set('cat_name', $params['cat_name']);
                $session->set('cat_desc', $params['cat_desc']);
                foreach ($result as $key => $message) {
                    if ($key == 'name') {
                        $this->addFlash('edit_error_name', $message);
                    }
                    if ($key == 'description') {
                        $this->addFlash('edit_error_desc', $message);
                    }
                }
                return $this->redirectToRoute('admin_category_index');
            }
            $this->addFlash('msg', 'Update category successfully.');
            return $this->redirectToRoute('admin_category_index');
        }
        return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/delete", name="delete", methods={"POST"})
     */
    public function deleteCategory (Request $request): Response
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete_category', $_token)) {
            $cat_id = $request->request->get('cat_id');
            $result = $this->categoryService->deleteCategoryById($cat_id);
            if ($result) {
                $this->addFlash('msg', 'Delete the category successfully.');
                return $this->redirectToRoute('admin_category_index');
            }
        }
        return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function createCategory (Request $request): Response
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('create_category', $_token)) {
            $params = $request->request->all();
            $result = $this->categoryService->createNewCategory($params);
            if (is_array($result)) {
                $session = $this->requestStack->getSession();
                $session->set('cat_name', $params['cat_name']);
                $session->set('cat_desc', $params['cat_desc']);
                foreach ($result as $key => $message) {
                    if ($key == 'name') {
                        $this->addFlash('create_error_name', $message);
                    }
                    if ($key == 'description') {
                        $this->addFlash('create_error_desc', $message);
                    }
                }
                return $this->redirectToRoute('admin_category_index');
            }
            return $this->redirectToRoute('admin_category_index');
        }
        return new Response('Operation not allowed!', Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);

    }

    /**
     * @Route("/recycle/list", name="recycle", methods={"GET", "HEAD"})
     */
    public function recycleCategory (Request $request): Response
    {
        $queryBuilder = $this->categoryService->getAllDeletedCategory();
        $categories = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
        return $this->render('/admin/dashboard/category/recycle.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/restore", name="restore", methods={"POST"})
     */
    public function restoreCategory (Request $request): Response
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('restore_category', $_token)) {
            $categoryId = $request->request->get('category_id');
            $result = $this->categoryService->restoreCategory($categoryId);
            if ($result) {
                $this->addFlash('msg', 'Restore category successfully.');
                return $this->redirectToRoute('admin_category_recycle');
            }
            $this->addFlash('msg', 'Restore category unsuccessfully. Please try again later.');
            return $this->redirectToRoute('admin_category_recycle');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

}
