<?php

namespace App\Controller\Api;

use App\Service\AiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/agent')]
class AgentController extends AbstractController
{
    #[Route('/run', methods: ['POST'])]
    public function run(Request $request, AiService $aiService): JsonResponse
    {
        try {
            $data = $request->toArray();
        } catch (\Exception) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid JSON'
            ], 400);
        }

        if (empty($data['goal'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Goal is required'
            ], 400);
        }

        $response = $aiService->ask($data['goal']);

        return $this->json([
            'status' => 'success',
            'response' => $response
        ]);
    }
}
