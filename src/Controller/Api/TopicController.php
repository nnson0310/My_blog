<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Api\TopicService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/topic", name="api_topic_")
 */
class TopicController extends AbstractController
{

    protected $topicService;

    protected $serializer;

    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/list", name="list")
     */
    public function getTopicList(): JsonResponse
    {
        $topics = $this->topicService->getAllTopics();

        //handle circular reference limit when an entity has relationship
        /* $data = $this->serializer->serialize($categories, 'json', [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            }
        ]); */
        
        if ($topics) {
            return new JsonResponse($topics, JsonResponse::HTTP_OK, ["Content-Type" => "application/json"]);
        }
        return new JsonResponse(["msg" => "No topics found."], JsonResponse::HTTP_FOUND, ["Content-type" => "application/json"]);
    }

    
}
