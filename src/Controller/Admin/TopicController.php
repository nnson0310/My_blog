<?php

namespace App\Controller\Admin;

use App\Services\Admin\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Admin\TopicService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Route("/backdoor/topic", name="admin_topic_")
 */
class TopicController extends AbstractController
{

    protected $topicService;

    protected $categoryService;

    protected $paginator;

    protected $requestStack;

    public function __construct (
        TopicService $topicService, 
        PaginatorInterface $paginator, 
        CategoryService $categoryService,
        RequestStack $requestStack
    )
    {
        $this->topicService = $topicService;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/index", name="index", methods={"GET", "HEAD"})
     */
    public function index (Request $request): Response
    {
        $queryBuilder = $this->topicService->getAllTopic();
        $paginatedTopics = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
        $categories = $this->categoryService->getAllCategory();
        return $this->render('admin/dashboard/topic/index.html.twig', [
            'paginatedTopics' => $paginatedTopics,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function createTopic (Request $request): Response
    {
        $_token = $request->request->get("_token");
        if ($this->isCsrfTokenValid('create_topic', $_token)) {
            $params = $request->request->all();
            $result = $this->topicService->createNewTopic($params);
            if (is_array($result)) {
                foreach ($result as $key => $message) {
                    if ($key == "name") {
                        $this->addFlash('create_error_name', $message);
                    }
                    if ($key == "description") {
                        $this->addFlash('create_error_desc', $message);
                    }
                }
                return $this->redirectToRoute('admin_topic_index');
            }
            else if (!$result) {
                $this->addFlash('msg', 'Create new topic unsuccessfully. Please try again later.');
                return $this->redirectToRoute('admin_topic_index');
            }
            $this->addFlash('msg', 'Create new topic successfully.');
            return $this->redirectToRoute('admin_topic_index');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/delete", name="delete", methods={"POST"})
     */
    public function deleteTopic (Request $request): Response
    {
        $_token = $request->request->get("_token");
        if ($this->isCsrfTokenValid("delete_topic", $_token)) {
            $params = $request->request->all();
            $result = $this->topicService->deleteTopic($params);
            if ($result) {
                $this->addFlash('msg', 'Delete the topic successfully.');
                return $this->redirectToRoute('admin_topic_index');
            }
            $this->addFlash('msg', 'Delete the topic unsuccessfully. Please try again.');
            return $this->redirectToRoute('admin_topic_index');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/update", name="update", methods={"POST"})
     */
    public function updateTopic (Request $request): Response
    {
        $_token = $request->request->get("_token");
        if ($this->isCsrfTokenValid("update_topic", $_token)) {
            $params = $request->request->all();
            $result = $this->topicService->updateTopic($params);
            if (is_array($result)) {
                foreach ($result as $key => $message) {
                    if ($key == 'name') {
                        $this->addFlash('edit_error_name', $message);
                    }
                    if ($key == 'description') {
                        $this->addFlash('edit_error_desc', $message);
                    }
                    return $this->redirectToRoute('admin_topic_index');
                }
            }
            else if (!$result) {
                $this->addFlash('msg', 'Update topic unsuccessfully. Please try again later.');
                return $this->redirectToRoute('admin_topic_index');
            }
            $this->addFlash('msg', 'Update topic successfully.');
            return $this->redirectToRoute('admin_topic_index');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/recycle/list", name="recycle", methods={"GET", "HEAD"})
     */
    public function recycleTopic (Request $request): Response
    {
        $queryBuilder = $this->topicService->getAllDeletedTopic();
        $topics = $this->paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 5);
        return $this->render('/admin/dashboard/topic/recycle.html.twig', [
            'topics' => $topics
        ]);
    }

    /**
     * @Route("/restore", name="restore", methods={"POST"})
     */
    public function restoreTopic (Request $request): Response
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('restore_topic', $_token)) {
            $topicId = $request->request->get('topic_id');
            $result = $this->topicService->restoreTopic($topicId);
            if ($result) {
                $this->addFlash('msg', 'Restore topic successfully.');
                return $this->redirectToRoute('admin_topic_recycle');
            }
            $this->addFlash('msg', 'Restore topic unsuccessfully. Please try again later.');
            return $this->redirectToRoute('admin_topic_recycle');
        }
        return new Response("Operation not allowed.", Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

}
