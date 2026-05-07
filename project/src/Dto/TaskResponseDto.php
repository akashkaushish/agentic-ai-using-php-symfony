<?php

namespace App\Dto;

class TaskResponseDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $status,
        public ?string $description
    ) {}
}
