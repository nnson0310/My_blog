<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Api\BlogService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Knp\Component\Pager\PaginatorInterface;


class BlogController extends AbstractController
{

    protected $blogService;

    protected $serializer;

    protected $paginator;

    public function __construct(BlogService $blogService, PaginatorInterface $paginator)
    {
        $this->blogService = $blogService;
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->paginator = $paginator;
    }

    /**
     * @Route("/api/blog/latest", name="api_blog_latest")
     */
    public function getLatestBlogs(): JsonResponse
    {
        $blogs = $this->blogService->getLatestBlogs();

        $blogInfo = $this->blogService->getLatestBlogInfo();

        $tagsName = [];
        foreach($blogs as $blog) {
            $tags = $blog->getTags();
            foreach($tags as $key => $tag) {
                $tagsName[$blog->getId()][$key] = $tag->getName();
            }
        }

        //handle circular reference limit when an entity has relationship
        /* $data = $this->serializer->serialize($blogs, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]);
        */
        
        if ($blogInfo && count($tagsName) > 0) {
            return new JsonResponse([
                "tags" => $tagsName,
                "blogs" => $blogInfo
            ], JsonResponse::HTTP_OK, ["Content-Type" => "application/json"]);
        }
        return new JsonResponse(["msg" => "No blog found."], JsonResponse::HTTP_FOUND, ["Content-type" => "application/json"]);
    }

    /**
     * @Route("/api/blog/details/{id}", name="api_blog_details")
     */
    public function getBlogDetails($id):JsonResponse
    {
        $blogDetails = $this->blogService->getBlogDetails($id);
        /* dd($blogDetails); */
 
        if ($blogDetails) {
            $content = $blogDetails->getContent();
            $title = $blogDetails->getTitle();
            $blogTags = $blogDetails->getTags();
            $createdAt = $blogDetails->getCreatedAt();
            $minRead = $blogDetails->getMinRead();
            foreach ($blogTags as $key => $tag) {
                $tags[$key] = $tag->getName();
            }
            return new JsonResponse([
                'content' => $content,
                'title' => $title,
                'tags' => $tags,
                'createdAt' => $createdAt,
                'minRead' => $minRead,
            ], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        }
        return new JsonResponse(["msg" => 'Blog '/'s details is not found.'], JsonResponse::HTTP_FOUND, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/api/paginated_posts/get", name="load_more_posts")
     */
    public function getPaginatedPosts(Request $request):JsonResponse
    {
        $page = $request->query->get('page');
        $queryBuilder = $this->blogService->getAllBlogs();
        $blogs = $this->paginator->paginate(
            $queryBuilder,
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

    /**
     * @Route("/api/category_posts/get", name="get_category_posts")
     */
    public function getCategoryPosts(Request $request):JsonResponse
    {
        $page = $request->query->get('page');
        $catId = $request->query->get('catId');
        $queryBuilder = $this->blogService->getAllCategoryPosts($catId);
        $blogs = $this->paginator->paginate(
            $queryBuilder,
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

    /**
     * @Route("/api/tag_posts/get", name="get_tag_posts")
     */
    public function getTagPosts(Request $request):JsonResponse
    {
        $page = $request->query->get('page');
        $tagSlug = $request->query->get('slug');
        $queryBuilder = $this->blogService->getAllTagPosts($tagSlug);
        $blogs = $this->paginator->paginate(
            $queryBuilder,
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

    /**
     * @Route("/api/feature_posts/get", name="get_feature_posts")
     */
    public function getFeaturePosts(Request $request):JsonResponse
    {
        $featurePosts = $this->blogService->getFeaturePosts();
       /*  $mostViewPosts = $this->blogService->getMostViewPosts();
        dd($mostViewPosts); */
        dd($featurePosts);
    }
}
