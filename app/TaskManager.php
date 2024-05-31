<?php

namespace ToDo\App;
require 'vendor/autoload.php';

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Carbon\Carbon;

class TaskManager
{
    private string $fileJson;
    private array $todos;

    public function __construct($fileJson = 'todos.json')
    {
        $this->fileJson = $fileJson;
        $this->todos = $this->loadTodos();
    }

    private function loadTodos(): array
    {
        if (file_exists($this->fileJson)) {
            $json = file_get_contents($this->fileJson);
            $todosData = json_decode($json, true);
            return array_map(['ToDo\App\Todo', 'fromArray'], $todosData);
        }
        return [];
    }

    private function saveTodos(): void
    {
        $json = json_encode(array_map(function ($todo) {
            return $todo->toArray();
        }, $this->todos));
        file_put_contents($this->fileJson, $json);
    }

    public function addTodo($description, $days = null): void
    {
        $deadline = $days ? Carbon::now()->addDays($days) : null;
        $this->todos[] = new Todo($description, 'pending', $deadline);
        $this->saveTodos();
    }

    public function getTodos(): array
    {
        return $this->todos;
    }

    public function markTodoCompleted($index): void
    {
        if (isset($this->todos[$index])) {
            $this->todos[$index]->markAsCompleted();
            $this->saveTodos();
        }
    }

    public function deleteTodo($index): void
    {
        if (isset($this->todos[$index])) {
            array_splice($this->todos, $index, 1);
            $this->saveTodos();
        }
    }

    private function showToDos(): void
    {
        $output = new ConsoleOutput();

        $table = new Table($output);
        $table->setHeaders(['Index', 'Description', 'Deadline', 'Status']);
        $index = 1;

        foreach ($this->todos as $todo) {
            $status = ($todo->getStatus() === 'completed') ? "\033[0;33mCompleted\033[0m" : "\033[0;31mPending\033[0m";
            $table->addRow([
                $index,
                $todo->getDescription(),
                $todo->getDeadline() ? $todo->getDeadline()->format('Y-m-d') : "No deadline",
                $status,
            ]);
            $index++;
        }
        $table->render();
    }

    public function display(): void {
        $this->showToDos();
    }
}