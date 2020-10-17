<?php

// Fetching overdue tasks
    function overdueTasks($connection) {
        $sql_overdue_tasks = "SELECT TaskID, TaskName, DueDate, CategoryDescription FROM Task INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND DueDate < NOW() AND CompletedDate IS NULL";

        // Clear the list to avoid duplicating all existing entries
        $overdue_tasks = null;
        $overdue_task_result = $connection->query($sql_overdue_tasks);

        if( !$overdue_task_result ) {
            exit("Something went wrong with the fetch");
        } 
        if( 0 === $overdue_task_result->num_rows ) {
            $overdue_tasks = "You have no active tasks";
        }
        if( $overdue_task_result->num_rows > 0 ) {
            while( $task = $overdue_task_result->fetch_assoc() ) {
                $overdue_tasks .= sprintf('
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
        return $overdue_tasks;
    }