<!DOCTYPE html>
<html>

<head>
    <title>Chat</title>
</head>

<body>
    <?php
    require 'db_connect.php';
    $sql = "SELECT * FROM messages";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>Message</th></tr>";
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["id"] . "</td><td>" . $row["message"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No messages found";
    }

    mysqli_close($conn);
    ?>
    
</body>

</html>