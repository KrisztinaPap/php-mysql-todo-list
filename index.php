<?php
    require 'constants.php';

    $todo_tasks = null;
    $overdue_tasks = null;
    $completed_tasks = null;
    // Variables for new tasks section
    $new_task = null;
    $new_due_date = null;
    $new_category = null;

    // Variables for Task lists
    $category = null;
    $task_name = null;
    $due_date = null;

    // SQL query variables for each status (for each todo list: todo, overdue, and completed)
    $sql_todo_tasks = "SELECT TaskName, DueDate, StatusDescription, CategoryDescription FROM Task INNER JOIN Status USING(StatusID) INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND StatusID = 1";

    $sql_overdue_tasks = "SELECT TaskName, DueDate, StatusDescription, CategoryDescription FROM Task INNER JOIN Status USING(StatusID) INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND StatusID = 2";

    $sql_completed_tasks = "SELECT TaskName, DueDate, StatusDescription, CategoryDescription FROM Task INNER JOIN Status USING(StatusID) INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND StatusID = 3";

    $connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);

    if( $connection->connect_errno) {
        die('Connection failed: '.$connection->connect_error);
    }

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
            </tr>
            ',
            $task['CategoryDescription'],
            $task['TaskName'],
            $task['DueDate']
            );       
        }
    }

    // Fetching overdue tasks
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
            </tr>
            ',
            $task['CategoryDescription'],
            $task['TaskName'],
            $task['DueDate']
            );       
        }
    }
    
    // Fetching completed tasks
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
            </tr>
            ',
            $task['CategoryDescription'],
            $task['TaskName'],
            $task['DueDate']
            );       
        }
    }

    $connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQLi + PHP To-Do List</title>

    <!-- Style(s) -->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    
    <!-- Script(s) -->
    <script type="text/JavaScript" src="js/scripts.js" defer></script>

</head>
<body>
    <h1>MySQLi + PHP To-Do List</h1>
    <form action="#" method="POST" enctype="multipart/form-data">
    <h2>Add New Task</h2>
        <p>
            <label for="new_task">Task</label>
            <input type="text" name="new_task" id="new_task">
        </p>
        <p>
            <label for="due_date">Due date</label>
            <input type="date" name="due_date" id="due_date">
        </p>
        <p>
            <label for="category">Task category</label>
            <select name="category" id="category">
                <option value="">Choose one</option>
            </select>
        </p>
        <p>
            <input type="submit" value="Add New Task">
        </p>
    </form>
    <section>
        <h2>Things to do</h2>
        <table>
            <?php echo $todo_tasks; ?>
        </table>
    </section>
    <section>
        <h2>Overdue</h2>
        <table>
            <?php echo $overdue_tasks; ?>
        </table>
    </section>
    <section>
        <h2>Completed</h2>
        <table>
            <?php echo $completed_tasks; ?>
        </table>
    </section>
    <?php
        include './templates/footer.php';
    ?>
</body>
</html>