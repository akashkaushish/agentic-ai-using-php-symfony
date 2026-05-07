<?php

namespace App\Transformer;

use App\Entity\Task;
use App\Dto\TaskResponseDto;

class TaskTransformer
{
    public function transform(Task $task): TaskResponseDto
    {
        return new TaskResponseDto(
            id: $task->getId(),
            title: $task->getTitle(),
            status: $task->getStatus(),
            description: $task->getDescription()
        );
    }

    public function transformCollection(array $tasks): array
    {
        return array_map(
            fn(Task $task) => $this->transform($task),
            $tasks
        );
    }
}
