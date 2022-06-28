<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Api\BlogService;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class FrontendController extends AbstractController
{

    protected $blogService;

    protected $paginator;

    public function __construct(
        BlogService $blogService,
        PaginatorInterface $paginator
    )
    {
        $this->blogService = $blogService;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/{path}", defaults={"path"=""})
     */
    public function index(): Response
    {
        return $this->render('frontend/index.html.twig');
    }

    /**
     * @Route("/api/search", name="api_search")
     */
    public function search(Request $request): JsonResponse
    {
        $keyword = $request->query->get("s");
        $page = $request->query->get("page");
        $query = $this->blogService->search(trim($keyword));
        $blogs = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', $page),
            8);
        $currentItems = $blogs->getItems();
        $itemsPerPage = $blogs->getItemNumberPerPage();
        $totalPages = $blogs->getPaginationData()['pageCount'];
        $currentPage = $blogs->getCurrentPageNumber();
        /* dd($blogs->getPaginationData()); */
        return new JsonResponse([
            "currentItems" => $currentItems, 
            'itemsPerPage' => $itemsPerPage, 
            'totalPages' => $totalPages, 
            'currentPage' => $currentPage
        ], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
