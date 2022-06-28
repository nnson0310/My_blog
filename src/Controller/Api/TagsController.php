<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Api\TagsService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/tags", name="api_tags_")
 */
class TagsController extends AbstractController
{

    protected $tagsService;

    protected $serializer;

    public function __construct(TagsService $tagsService)
    {
        $this->tagsService = $tagsService;
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/list", name="list")
     */
    public function getTagsList(): JsonResponse
    {
        $tags = $this->tagsService->getAllTags();
        return new JsonResponse($tags, JsonResponse::HTTP_OK, ["Content-Type" => "application/json"]);
    }

    
}
