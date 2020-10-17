<?php

// Fetching completed tasks
function completedTasks($connection) {
    $sql_completed_tasks = "SELECT TaskID, TaskName, DueDate, CategoryDescription FROM Task INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND CompletedDate IS NOT NULL";

    // Clear the list to avoid duplicating all existing entries
    $completed_tasks = null;

    $completed_task_result = $connection->query($sql_completed_tasks);

    if( !$completed_task_result ) {
        exit("Something went wrong with the fetch");
    } 
    if( 0 === $completed_task_result->num_rows ) {
        $completed_tasks = "You have no active tasks";
    }
    if( $completed_task_result->num_rows > 0 ) {
        while( $task = $completed_task_result->fetch_assoc() ) {
            $completed_tasks .= sprintf('
            <tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td><button type="submit" name="soft_delete" value="%d" class="button">DELETE</button></td>
                <td><button type="submit" name="unComplete" value="%d" class="button">Reactivate</button></td>
            </tr>
            ',
            $task['CategoryDescription'],
            $task['TaskName'],
            $task['DueDate'],
            $task['TaskID'],
            $task['TaskID']
            );       
        }
    }
    return $completed_tasks;
}