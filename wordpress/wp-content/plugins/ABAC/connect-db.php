<!DOCTYPE html>
<html>
<head>
    <title>PHP Script</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
</head>

<body>
    <?php 

        // Connect to the database for the project.
        $dbLocalhost = mysqli_connect("localhost", "root", "", "wordpress")
            or die("Could not connect: " . mysql_error());
        
    ?>
</body>
</html>