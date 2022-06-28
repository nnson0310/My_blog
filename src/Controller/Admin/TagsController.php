<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Admin\TagsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
* @Route("/backdoor/tags", name="admin_tags_")
*/
class TagsController extends AbstractController
{

    protected $tagsService;

    protected $paginator;

    protected $requestStack;

    public function __construct(
        TagsService $tagsService, 
        PaginatorInterface $paginator,
        RequestStack $requestStack
    )
    {
        $this->tagsService = $tagsService;
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/index", name="index")
     */
    public function index(Request $request): Response
    {
        $queryBuilder = $this->tagsService->getAllTags();
        $tags = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
        return $this->render('admin/dashboard/tags/index.html.twig', [
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function createTags (Request $request): Response
    {
        $_token = $request->request->get("_token");
        if ($this->isCsrfTokenValid('create_tags', $_token)) {
            $tagsName = $request->request->get('tags_name');
            $result = $this->tagsService->createNewTags($tagsName);
            if (is_array($result)) {
                foreach ($result as $message) {
                    $this->addFlash('create_error', $message);
                }
                return $this->redirectToRoute('admin_tags_index');
            }
            else if (!$result) {
                $this->addFlash('msg', 'Create new tags unsuccessfully. Please try again later.');
                return $this->redirectToRoute('admin_tags_index');
            }
            $this->addFlash('msg', 'Create new tags successfully.');
            return $this->redirectToRoute('admin_tags_index');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/delete", name="delete", methods={"POST"})
     */
    public function deleteTags (Request $request): Response
    {
        $_token = $request->request->get("_token");
        if ($this->isCsrfTokenValid("delete_tags", $_token)) {
            $tagsId = $request->request->get('tags_id');
            $result = $this->tagsService->deleteTags($tagsId);
            if ($result) {
                $this->addFlash('msg', 'Delete the tags successfully.');
                return $this->redirectToRoute('admin_tags_index');
            }
            $this->addFlash('msg', 'Delete the tags unsuccessfully. Please try again.');
            return $this->redirectToRoute('admin_tags_index');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/update", name="update", methods={"POST"})
     */
    public function updateTags (Request $request): Response
    {
        $_token = $request->request->get("_token");
        if ($this->isCsrfTokenValid("update_tags", $_token)) {
            $params = $request->request->all();
            $result = $this->tagsService->updateTags($params);
            if (is_array($result)) {
                foreach ($result as $message) {
                    $this->addFlash('edit_error', $message);
                    return $this->redirectToRoute('admin_tags_index');
                }
            }
            else if (!$result) {
                $this->addFlash('msg', 'Update tags unsuccessfully. Please try again later.');
                return $this->redirectToRoute('admin_tags_index');
            }
            $this->addFlash('msg', 'Update tags successfully.');
            return $this->redirectToRoute('admin_tags_index');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/recycle/list", name="recycle", methods={"GET", "HEAD"})
     */
    public function recycleTags (Request $request): Response
    {
        $queryBuilder = $this->tagsService->getAllDeletedTags();
        $tags = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
        return $this->render('/admin/dashboard/tags/recycle.html.twig', [
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/restore", name="restore", methods={"POST"})
     */
    public function restoreTags (Request $request): Response
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('restore_tags', $_token)) {
            $tagsId = $request->request->get('tags_id');
            $result = $this->tagsService->restoreTags($tagsId);
            if ($result) {
                $this->addFlash('msg', 'Restore tags successfully.');
                return $this->redirectToRoute('admin_tags_recycle');
            }
            $this->addFlash('msg', 'Restore tags unsuccessfully. Please try again later.');
            return $this->redirectToRoute('admin_tags_recycle');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }
}
