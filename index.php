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
    </section>
    <section>
        <h2>Overdue</h2>
    </section>
    <section>
        <h2>Completed</h2>
    </section>
    <?php
        include './templates/footer.php';
    ?>
</body>
</html>