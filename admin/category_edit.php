<?php
    require '../constants.php';

    $message = null;
    $connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);

    if( $connection->connect_errno) {
        die('Connection failed: ' . $connection->connect_error);
    }

    function categoryFetch($connection) {
        // Clear the list to avoid duplicating all existing entries
        $task_categories = null;
        $sql_task_categories = "SELECT * FROM Category";
        $task_category_results = $connection->query($sql_task_categories);

        if( !$task_category_results ) {
            echo "Something went wrong with the task categories fetch!";
            exit();
        }

        if( $task_category_results->num_rows > 0 ) {
            while( $category = $task_category_results->fetch_assoc() ) {
                $task_categories .= sprintf('
                <li>%s<button type="submit" name="hard_delete" value="%d">DELETE</button></li>
                ',
                $category['CategoryDescription'],
                $category['CategoryID']
                );      
            }
        }
        return $task_categories;
    }

    $task_categories = categoryFetch($connection);

    if(isset($_POST['add'])) {

        // Prepared statement
        if( $stmt = $connection->prepare("INSERT INTO Category(CategoryID, CategoryDescription) VALUES (NULL, ?)") ) {
            if( $stmt->bind_param("s", $_POST['new_category']) ) {
                if( $stmt->execute() ) {
                    $task_categories = categoryFetch($connection);
                } else {
                    exit("There was a problem with adding your new category...");
                } 
            } else {
                exit("There was a problem with the bind_param");
            }
        } else {
            exit("There was a problem with the prepare statement");
        }
       
        $stmt->close();
    }

    else if(isset($_POST['hard_delete'])) {

        $category_id = $_POST['hard_delete'];

        // See if a task is using this category
        $sql_category_in_use = "SELECT * FROM Category INNER JOIN Task USING(CategoryID) WHERE CategoryID=$category_id";
        $category_in_use_result = $connection->query($sql_category_in_use);

        if( $category_in_use_result->num_rows > 0 ) {
            $message = "That category is in use! Please fix it and come back!";
        } else {
            $sql_hard_delete = "DELETE FROM Category WHERE CategoryID=$category_id";
            $hard_delete_result = $connection->query($sql_hard_delete);
    
            if( !$hard_delete_result ) {
                exit("Something went wrong with hard deleting your category");
            } else {
                $task_categories = categoryFetch($connection);
            }
        }       
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Category Edit</title>
    </head>
    <body>
        <a href="../index.php">Home</a>
        <h1>Category Edit Screen</h1>

        <form action="#" method="POST" enctype="multipart/form-data">

            <h2>Existing Categories</h2>
                <?php echo $task_categories; ?>

            <h2>Add New</h2>
                <p>
                    <label for="new_category">Category Name</label>
                    <input type="text" name="new_category" id="new_category">
        
                    <input type="submit" name="add" value="Add">
                    <?php if($message) echo $message; ?>
                </p>
        </form>
    </body>
</html>