<?php
session_start();
include 'db_conn.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    $recipeName = mysqli_real_escape_string($conn, $_POST['recipeName']);
    $recipeDescription = mysqli_real_escape_string($conn, $_POST['recipeDescription']);

    // Handle file upload
    if (isset($_FILES['recipeImage']) && $_FILES['recipeImage']['error'] == 0) {
        $fileTmpPath = $_FILES['recipeImage']['tmp_name'];
        $fileName = $_FILES['recipeImage']['name'];
        $fileSize = $_FILES['recipeImage']['size'];
        $fileType = $_FILES['recipeImage']['type'];
        
        // Specify the directory to save the uploaded image
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $fileName;

        // Move the uploaded file to the specified directory
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            // Insert recipe data into the database
            $query = "INSERT INTO recipes (title, description, image) VALUES ('$recipeName', '$recipeDescription', '$dest_path')";
            if (mysqli_query($conn, $query)) {
                echo "Recipe posted successfully!";
                header("Location: index.php"); // Redirect to your homepage or another page
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "There was an error moving the uploaded file.";
        }
    } else {
        echo "No image uploaded or there was an upload error.";
    }
}

mysqli_close($conn); // Close database connection
?>
