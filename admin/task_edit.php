<?php
    require '../constants.php';
    $task_id = $_GET['task_id'];
    $message = null;

    // If we don't have a task id, do not continue
    if( !isset($_GET['task_id']) || $_GET['task_id'] === "" ) {
        exit("The page you are looking for doesn't exist!");
    }

    // If the task id is not an INT, do not continue
    if( filter_var($_GET['task_id'], FILTER_VALIDATE_INT ) ) {
        $task_id = $_GET['task_id'];
    } else {
        exit("An incorrect value was passed");
    }

    $sql_edit_task = "SELECT * FROM Task INNER JOIN Category USING(CategoryID) WHERE TaskID = $task_id";

    $connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);

    if( $connection->connect_errno) {
        die('Connection failed: ' . $connection->connect_error);
    }
    $edit_task_result = $connection->query($sql_edit_task);
    
    if( !$edit_task_result ) {
        exit("Something went wrong with the task query");
    }
    if( 0 === $edit_task_result->num_rows) {
        exit("There is no such task!");
    }
    while( $row = $edit_task_result->fetch_assoc() ) {
            $task_name = $row['TaskName'];
            $due_date = $row['DueDate'];
            $task_category = $row['CategoryDescription'];
    }  

    require_once('../includes/category_dropdown.php');
 
    // Fetch category options for the dropdown
    $task_category_options = categoryDropdown($connection);

    if( $_POST ) {
        if( $statement = $connection->prepare("UPDATE Task SET TaskName=?, DueDate=?, CategoryID=? WHERE TaskID=$task_id")) {
            if( $statement->bind_param("ssi", $_POST['task_name'], $_POST['due_date'], $_POST['category']) ) {
                if( $statement->execute() ) {
                   $message = "Update successful!";
                   require_once('../includes/todo_fetch.php');
                   require_once('../includes/overdue_fetch.php');
                   require_once('../includes/completed_fetch.php');
                   require_once('../includes/soft_deleted_fetch.php');
                } else {
                    exit("There was a problem with the execute");
                }
            } else {
                exit("There was a problem with the bind_param");
            }
        } else {
            exit("There was a problem with the prepare statement");
        }
        $statement->close();
    }


    $connection->close();    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>

    <!-- Style(s) -->
    <link rel="stylesheet" type="text/css" href="../css/main.css" />
    
</head>
<body>
    <a href="../index.php" class="button">Home</a>
    <h1>Edit Task</h1>
    <form action="#" method="POST" enctype="multipart/form-data">
        <p>
            <label for="task_name">Task Name</label>
            <input type="text" name="task_name" id="task_name" value="<?php echo $task_name; ?>" required>
        </p>
        <p>
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" value="<?php echo $due_date; ?>" required>
        </p>
        <p>
            <label for="category">Current Task Category: <strong><?php echo $task_category; ?></strong></label>
        </p>
        <p>
            <select name="category" id="category" value="" required>
                <option value="">Choose another</option>
                <?php echo $task_category_options; ?>
            </select>
        </p>
        <p>
            <input type="submit" value="Update" class="button">
            <span class="message"><?php if($message) echo $message; ?></span>
        </p>
    </form>
</body>
</html>