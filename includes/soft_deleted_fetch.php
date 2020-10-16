<?php

function softDeletedTasks($connection) {
    $sql_soft_deleted_tasks = "SELECT TaskID, TaskName, DueDate, CategoryDescription FROM Task INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 2";

    // Clear the list to avoid duplicating all existing entries
    $soft_deleted_tasks = null;

    $soft_deleted_task_result = $connection->query($sql_soft_deleted_tasks);

    if( !$soft_deleted_task_result ) {
        exit("Something went wrong with the fetch");
    } 
    if( 0 === $soft_deleted_task_result->num_rows ) {
        $soft_deleted_tasks = "You have no active tasks";
    }
    if( $soft_deleted_task_result->num_rows > 0 ) {
        while( $task = $soft_deleted_task_result->fetch_assoc() ) {
            $soft_deleted_tasks .= sprintf('
            <tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td><button type="submit" name="hard_delete" value="%d">HARD DELETE</button></td>
            </tr>
            ',
            $task['CategoryDescription'],
            $task['TaskName'],
            $task['DueDate'],
            $task['TaskID']
            );       
        }
    }
    return $soft_deleted_tasks;
}