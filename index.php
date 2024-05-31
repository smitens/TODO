<?php
require 'vendor/autoload.php';

use ToDo\App\TaskManager;

$taskManager = new TaskManager();

function displayMenu(): void {
    echo "1. Create new task\n";
    echo "2. Display list of Your tasks\n";
    echo "3. Mark task as completed\n";
    echo "4. Delete task\n";
    echo "5. Exit\n";
    echo "Enter the number of your choice: ";
}

echo "\n\033[1m\033[4mWelcome to Your ToDo list!\033[0m\n\033[0m\n";
while (true) {
    displayMenu();
    $choice = trim(fgets(STDIN));

    switch ($choice) {
        case '1':
            $description = trim(readline("Enter the task description: "));
            $daysInput = trim(readline("Enter the number of days for the deadline or leave blank for no deadline: "));
            $days = is_numeric($daysInput) ? (int)$daysInput : null;
            $taskManager->addTodo($description, $days);
            break;
        case '2':
            $todos = $taskManager->getTodos();
            $taskManager->display();
            break;
        case '3':
            $index = (int)trim(readline("Enter the index of the task to mark as completed: ")) - 1;
            $taskManager->markTodoCompleted($index);
            break;
        case '4':
            $index = (int)trim(readline("Enter the number of the task to delete: ")) - 1;
            $taskManager->deleteTodo($index);
            break;
        case '5':
            echo "\n";
            exit("\033[1mHave a productive day! Goodbye!\033[0m\n");
        default:
            echo "Invalid choice, please try again.\n";
    }
}