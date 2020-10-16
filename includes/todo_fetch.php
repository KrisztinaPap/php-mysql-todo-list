<?php

// Fetching todo tasks
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
            <td><button type="submit" name="soft_delete" value="%d">DELETE</button></td>
            <td><a href="task_delete.php?task_id=%d">Done</a></td>
            <td><a href="task_edit.php?task_id=%d">Edit</a></td>
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

?>