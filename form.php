<html>
<body>

<!-- form fields: name, position, supervisor, email -->

<div class="container">
    <div class="row">
        <?php echo $_POST["name"]; ?><br>
        <?php echo $_POST["position"]; ?><br>
        <?php echo $_POST["supervisor"]; ?><br>
        <?php echo $_POST["email"]; ?> <br>
    </div>

<!-- connect mydql database -->
    <div class="row">
        <?php
            // database config
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "employee_information";
            //post data
            $name = $_POST["name"];
            $position = $_POST["position"];
            $supervisor = $_POST["supervisor"];
            $email = $_POST["email"];

            //create connection
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            //check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            //insert employee basic information: name, position, supervisor, email
            $sql = "INSERT INTO `users` (`employee_id`, `name`, `position`, `supervisor`, `email`) 
                    VALUES (NULL, '$name', '$position', '$supervisor', '$email')";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
            //end of inserting employee information

        ?>
    </div>
</div>



</body>
</html>