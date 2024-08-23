<?php
// Include your database connection script
require 'db_connect.php';

$message = $_POST['message'];

$sql = "INSERT INTO messages ( message) VALUES ( '$message')";

if (mysqli_query($conn, $sql)) {
    echo "Message sent successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


header("Location: http://localhost/sockets-test/");
exit();