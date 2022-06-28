<?php

namespace App\Controller\Api;

use App\Services\Api\SubscriberService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriberController extends AbstractController
{

    private $subscribeService;

    public function __construct(SubscriberService $subscribeService)
    {
        $this->subscribeService = $subscribeService;
    }

    /**
     * @Route("/api/subscribe", name="api_subscribe")
     */
    public function subscribe(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->subscribeService->storeSubscribedEmail($data);
        if (is_string($result)) {
            return new JsonResponse([
                'msg' => $result,
                'status' => false
            ], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        }
        else if ($result) {
            return new JsonResponse([
                'msg' => 'You have been subscribed successfully.'
            ], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        }
        return new JsonResponse([
            'msg' => 'You can not subsribe right now due to some server error. Please contact us directly by email or try again later.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json']);
    }
}
