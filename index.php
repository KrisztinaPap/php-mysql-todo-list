<?php
    require 'constants.php';

    $message = null;

    $connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);

    if( $connection->connect_errno) {
        die('Connection failed: '.$connection->connect_error);
    }

    // Fetch content for todo lists (todo, overdue, completed, and soft-deleted) and category dropdown (select)
    require_once('includes/todo_fetch.php');
    require_once('includes/overdue_fetch.php');
    require_once('includes/completed_fetch.php');
    require_once('includes/soft_deleted_fetch.php');
    require_once('includes/category_dropdown.php');

    $todo_tasks = toDoTasks($connection);
    $overdue_tasks = overdueTasks($connection);
    $completed_tasks = completedTasks($connection);
    $soft_deleted_tasks = softDeletedTasks($connection);
    $task_category_options = categoryDropdown($connection);

    // If the form is submitted with the add button
    if(isset($_POST['add'])) {

        // Only add new task if user input is not empty string
        if( $_POST['new_task'] === "" ) {
            $message = "Please enter a task name to add!";
        } else {
            // Prepared statement
            if( $stmt = $connection->prepare("INSERT INTO Task(TaskID, CategoryID, ActiveID, TaskName, DueDate, CompletedDate) VALUES (NULL, ?, 1, ?, ?, NULL)") ) {
                if( $stmt->bind_param("iss", $_POST['category'], $_POST['new_task'], $_POST['due_date']) ) {
                    if( $stmt->execute() ) {
                        $message = "Task was added!";

                        // Fetch content of todo lists (todo, overdue, completed, and soft-deleted)
                        $todo_tasks = toDoTasks($connection);
                        $overdue_tasks = overdueTasks($connection);
                        $completed_tasks = completedTasks($connection);
                        $soft_deleted_tasks = softDeletedTasks($connection);
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
    }
    // If the form is submitted with a delete button, do a soft-delete
    else if(isset($_POST['soft_delete'])) {

        $task_id = $_POST['soft_delete'];
        $sql_soft_delete = "UPDATE Task SET ActiveID=2 WHERE TaskID=$task_id";
        $soft_delete_result = $connection->query($sql_soft_delete);

        if( !$soft_delete_result ) {
            exit("Something went wrong with the soft delete");
        } 
        if( $soft_delete_result ) {
            // Fetch content of todo lists (todo, overdue, completed, and soft-deleted)
            $todo_tasks = toDoTasks($connection);
            $overdue_tasks = overdueTasks($connection);
            $completed_tasks = completedTasks($connection);
            $soft_deleted_tasks = softDeletedTasks($connection);
        }
    }

    // If the form is submitted with a completed button, update CompletedDate to current date
    else if(isset($_POST['complete'])) {

        $task_id = $_POST['complete'];
        $sql_complete = "UPDATE Task SET CompletedDate=NOW() WHERE TaskID=$task_id";
        $complete_result = $connection->query($sql_complete);

        if( !$complete_result ) {
            exit("Something went wrong with completing your task");
        } 
        if( $complete_result ) {
            // Fetch content of todo lists (todo, overdue, completed, and soft-deleted)
            $todo_tasks = toDoTasks($connection);
            $overdue_tasks = overdueTasks($connection);
            $completed_tasks = completedTasks($connection);
            $soft_deleted_tasks = softDeletedTasks($connection);
        }
    }

    // If the form is submitted with a reactivate button, un-complete it (reverse of complete)
    else if(isset($_POST['unComplete'])) {

        $task_id = $_POST['unComplete'];
        $sql_unComplete = "UPDATE Task SET CompletedDate=NULL WHERE TaskID=$task_id";
        $unComplete_result = $connection->query($sql_unComplete);

        if( !$unComplete_result ) {
            exit("Something went wrong with re-activating your task");
        } else {
            // Fetch content of todo lists (todo, overdue, completed, and soft-deleted)
            $todo_tasks = toDoTasks($connection);
            $overdue_tasks = overdueTasks($connection);
            $completed_tasks = completedTasks($connection);
            $soft_deleted_tasks = softDeletedTasks($connection);
        }
    }

    // If the form is submitted with a hard delete button, actually delete row from database
    else if(isset($_POST['hard_delete'])) {

        $task_id = $_POST['hard_delete'];
        $sql_hard_delete = "DELETE FROM Task WHERE TaskID=$task_id";
        $hard_delete_result = $connection->query($sql_hard_delete);

        if( !$hard_delete_result ) {
            exit("Something went wrong with hard deleting your task");
        } else {
            // Fetch content of todo lists (todo, overdue, completed, and soft-deleted)
            $todo_tasks = toDoTasks($connection);
            $overdue_tasks = overdueTasks($connection);
            $completed_tasks = completedTasks($connection);
            $soft_deleted_tasks = softDeletedTasks($connection);
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
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    
    <!-- Script(s) -->
    <script type="text/JavaScript" src="js/scripts.js" defer></script>
</head>
<body>
    <h1>MySQLi + PHP To-Do List</h1>
    <section>   
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
                <a href="admin/category_edit.php" class="button">Edit Categories</a>
            </p>
            <p>
                <input type="submit" name="add" value="Add New Task" class="button">
                <span class="message"><?php if($message) echo $message; ?></span>
            </p>
    </section>
    <section>
        <h2>Things to do</h2>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Task</th>
                    <th>Due Date</th>
                </tr>
                <?php echo $todo_tasks; ?>
            </table>
    </section>
    <section>
        <h2>Overdue</h2>
        <table>
            <tr>
                <th>Category</th>
                <th>Task</th>
                <th>Due Date</th>
            </tr>
            <?php echo $overdue_tasks; ?>
        </table>
    </section>
    <section>
        <button id="completed_btn" class="button">Toggle Completed Tasks</button>
        <div id="completed_div" class="hidden">
            <h2>Completed Tasks</h2>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Task</th>
                    <th>Due Date</th>
                </tr>
                <?php echo $completed_tasks; ?>
            </table>
        </div>
    </section>
    <section>
        <button id="deleted_btn" class="button">Toggle Deleted Tasks</button>
        <div id="deleted_div" class="hidden">
            <h2>Deleted Tasks (Soft-deleted)</h2>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Task</th>
                    <th>Due Date</th>
                </tr>
                <?php echo $soft_deleted_tasks; ?>
            </table>
        </div>
    </section>
    </form>
    <?php
        include './templates/footer.php';
    ?>
</body>
</html>