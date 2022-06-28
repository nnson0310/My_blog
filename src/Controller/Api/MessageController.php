<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Services\Api\MessageService;

/**
 * @Route("/api/message", name="api_message_")
 */
class MessageController extends AbstractController
{
    protected $messageService;

    protected $serializer;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/send", name="send", methods={"POST"})
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $message = json_decode($request->getContent(), true);
        $result = $this->messageService->storeMessage($message);
        if ($result) {
            return new JsonResponse([
                'msg' => 'Thank you so much for sending message to us! We will reply soon.'
            ], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        }
        return new JsonResponse([
            'msg' => 'We can not receive your message right now due to some server error. Sorry for the inconvenient. Please contact us through email instead.'
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
    }
}
