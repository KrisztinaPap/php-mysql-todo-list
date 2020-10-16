<?php

 // Fetching task categories for the dropdown
 function categoryDropdown($connection) {
    // SQL query variables
    $sql_task_categories = "SELECT CategoryID, CategoryDescription FROM Category";

    // Clear the list to avoid duplicating all existing entries
    $task_category_options = null;
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
    return $task_category_options;
}
