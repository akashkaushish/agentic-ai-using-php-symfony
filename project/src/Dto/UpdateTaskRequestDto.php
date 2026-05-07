<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTaskRequestDto
{
    #[Assert\Length(min: 3, max: 255)]
    public ?string $title = null;

    #[Assert\Choice(
        choices: ['pending', 'in_progress', 'completed'],
        message: 'Status must be one of: pending, in_progress, completed.'
    )]
    public ?string $status = null;

    #[Assert\Length(max: 1000)]
    public ?string $description = null;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->description = $data['description'] ?? null;
    }
}
