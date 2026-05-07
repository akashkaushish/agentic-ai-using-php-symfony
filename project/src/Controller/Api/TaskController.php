<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\TaskService;
use App\Dto\CreateTaskRequestDto;
use App\Dto\UpdateTaskRequestDto;

#[Route('/api/tasks')]
class TaskController extends AbstractController
{
    #[Route('', name: 'task_list', methods: ['GET'])]
    public function list(TaskRepository $taskRepository): JsonResponse
    {
        $tasks = $taskRepository->findAll();

        $data = array_map(function (Task $task) {
            return $this->formatTask($task);
        }, $tasks);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): JsonResponse
    {
        return $this->json($this->formatTask($task));
    }

    #[Route('', name: 'task_create', methods: ['POST'])]
    public function create(
        Request $request,
        TaskService $taskService,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $data = $request->toArray();
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid JSON body'
            ], 400);
        }

        $dto = new CreateTaskRequestDto($data);
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json([
                'status' => 'error',
                'errors' => $this->formatValidationErrors($errors)
            ], 422);
        }

        $result = $taskService->create($dto);

        return $this->json([
            'status' => 'success',
            'data' => $result
        ], 201);
    }

    #[Route('/{id}', name: 'task_update', methods: ['PUT'])]
    public function update(
        Task $task,
        Request $request,
        TaskService $taskService,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $data = $request->toArray();
        } catch (\Exception $e) {
             return $this->json([
                'status' => 'error',
                'message' => 'Invalid JSON body'
            ], 400);
        }

        $dto = new UpdateTaskRequestDto($data);
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json([
                'status' => 'error',
                'errors' => $this->formatValidationErrors($errors)
            ], 422);
        }

        $result = $taskService->update($task, $dto);

        return $this->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    #[Route('/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function delete(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->json([
            'message' => 'Task deleted successfully'
        ]);
    }

    private function formatTask(Task $task): array
    {
        return [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'status' => $task->getStatus(),
            'description' => $task->getDescription(),
        ];
    }

    private function formatValidationErrors($errors): array
    {
        $formattedErrors = [];

        foreach ($errors as $error) {
            $formattedErrors[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return $formattedErrors;
    }
}
