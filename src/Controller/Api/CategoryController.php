<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Api\CategoryService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/category", name="api_category_")
 */
class CategoryController extends AbstractController
{

    protected $categoryService;

    protected $serializer;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/list", name="list")
     */
    public function getCategoryList(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();

        //handle circular reference limit when an entity has relationship
        /* $data = $this->serializer->serialize($categories, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]); */
        /* dd($categories); */
        if ($categories) {
            return new JsonResponse($categories, JsonResponse::HTTP_OK, ["Content-Type" => "application/json"]);
        }
        return new JsonResponse(["msg" => "No category found."], JsonResponse::HTTP_FOUND, ["Content-type" => "application/json"]);
    }
}
