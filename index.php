<?php
    require 'constants.php';

    // For the Task Categories dropdown
    $task_category_options = null;
    $sql_task_categories = "SELECT CategoryID, CategoryDescription FROM Category";

    // Variables for the 3 different task lists (todo(active), overdue, and completed)
    $todo_tasks = null;
    $overdue_tasks = null;
    $completed_tasks = null;
    $message = null;

    // SQL query variables for each status (for each todo list: todo, overdue, and completed)
    $sql_todo_tasks = "SELECT TaskName, DueDate, CategoryDescription FROM Task INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND DueDate > NOW()";

    $sql_overdue_tasks = "SELECT TaskName, DueDate, CategoryDescription FROM Task INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND DueDate < NOW()";

    $sql_completed_tasks = "SELECT TaskName, DueDate, CategoryDescription FROM Task INNER JOIN Category USING(CategoryID) INNER JOIN Active USING(ActiveID) WHERE ActiveID = 1 AND CompletedDate IS NOT NULL";

    // // SQL for inserting new task
    // $sql_insert_new_task = "INSERT INTO Task (TaskID, CategoryID, ActiveID, TaskName, DueDate, CompletedDate)
    // VALUES (NULL, 2, 1, 'Math homework', '1900-01-01', NULL)";

    $connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);

    if( $connection->connect_errno) {
        die('Connection failed: '.$connection->connect_error);
    }

    // Fetching task categories for the dropdown
    $task_category_results = $connection->query($sql_task_categories);

    if( !$task_category_results ) {
        echo "Something went wrong with the task categories fetch!";
        exit();
    }

    if( $task_category_results->num_rows > 0 ) {
        while( $category = $task_category_results->fetch_assoc() ) {
            $task_category_options .= sprintf('<option value="%s">%s</option>',
                $category['CategoryID'],
                $category['CategoryDescription']
            );
        }
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

    if( $_POST ) {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        // Prepared statement
        if( $stmt = $connection->prepare("INSERT INTO Task(TaskID, CategoryID, ActiveID, TaskName, DueDate, CompletedDate) VALUES (NULL, ?, 1, ?, ?, NULL)") ) {
            if( $stmt->bind_param("iss", $_POST['category'], $_POST['new_task'], $_POST['due_date']) ) {
                if( $stmt->execute() ) {
                    $message = "Task was added!";
                } else {
                    exit("There was a problem with adding your new task...");
                } 
            } else {
                exit("There was a problem with the bind_param");
            }
        } else {
            exit("There was a problem with the prepare statement");
        }
       
        $stmt->close();
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
    <?php if($message) echo $message; ?>
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
                <?php echo $task_category_options; ?>
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