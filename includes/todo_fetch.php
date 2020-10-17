<?php

// Fetching todo tasks
    function toDoTasks($connection) {
        // SQL query variables for each status (for each todo list: todo, overdue, and completed)
        $sql_todo_tasks = "SELECT TaskID, TaskName, DueDate, CategoryDescription FROM Task INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND DueDate > NOW() AND CompletedDate IS NULL";

        // Clear the list to avoid duplicating all existing entries
        $todo_tasks = null;
        $todo_task_result = $connection->query($sql_todo_tasks);     

        if( !$todo_task_result ) {
            exit("Something went wrong with the fetch");
        } 
        if( 0 === $todo_task_result->num_rows ) {
            $tasks = "You have no active tasks";
        }
        if( $todo_task_result->num_rows > 0 ) {
            while( $task = $todo_task_result->fetch_assoc() ) {
                $todo_tasks .= sprintf('
                <tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td><button type="submit" name="soft_delete" value="%d" class="button">DELETE</button></td>
                    <td><button type="submit" name="complete" value="%d" class="button">Complete</button></td>
                    <td><a href="admin/task_edit.php?task_id=%d" class="button">Edit</a></td>
                </tr>
                ',
                $task['CategoryDescription'],
                $task['TaskName'],
                $task['DueDate'],
                $task['TaskID'],
                $task['TaskID'],
                $task['TaskID']
                );       
            }
        }
        return $todo_tasks;
    }