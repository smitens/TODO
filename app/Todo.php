<?php

namespace ToDo\App;
require 'vendor/autoload.php';
use Carbon\Carbon;

class Todo
{
    private string $description;
    private string $status;
    private ?Carbon $deadline;

    public function __construct($description, $status = 'pending', $deadline = null) {
        $this->description = $description;
        $this->status = $status;
        $this->deadline = $deadline ? Carbon::parse($deadline) : null;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getDeadline(): ?Carbon
    {
        return $this->deadline;
    }

    public function markAsCompleted(): void
    {
        $this->status = 'completed';
    }


    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'status' => $this->status,
            'deadline' => $this->deadline ? $this->deadline->toIso8601String() : null,
        ];
    }

    public static function fromArray($data): Todo
    {
        return new self(
            $data['description'],
            $data['status'],
            isset($data['deadline']) ? Carbon::parse($data['deadline']) : null
        );
    }
}