<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\CreateTaskRequestDto;
use App\Dto\UpdateTaskRequestDto;
use App\Dto\TaskResponseDto;
use App\Transformer\TaskTransformer;

class TaskService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TaskTransformer $transformer
    ) {}

    public function create(CreateTaskRequestDto $dto): TaskResponseDto
    {
        $task = new Task();
        $task->setTitle($dto->title);
        $task->setStatus($dto->status);
        $task->setDescription($dto->description);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->transformer->transform($task);
    }

    public function update(Task $task, UpdateTaskRequestDto $dto): TaskResponseDto
    {
        if ($dto->title !== null) {
            $task->setTitle($dto->title);
        }

        if ($dto->status !== null) {
            $task->setStatus($dto->status);
        }

        if ($dto->description !== null) {
            $task->setDescription($dto->description);
        }

        $this->entityManager->flush();

        return $this->transformer->transform($task);
    }

    private function formatErrors($errors): array
    {
        $result = [];

        foreach ($errors as $error) {
            $result[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }

        return $result;
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
}
