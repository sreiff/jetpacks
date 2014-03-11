<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Adding Mouse</title>
</head>
<body>
<p>Adding Mouse</p>
    
<?php
// Connect to MySQL
    require ('../../mysqli_connect.php'); // for iPage
    //require ('../../../../mysqli_connect.php'); 

$dbname = 'jetpack';

// Select the database:
    $q = "USE $dbname";    
    $r = @mysqli_query ($dbc, $q); 

if ($r) {
    echo "<p>using $dbname</p>";
}

// Insert a new user:
$fn = "Mickey";
$ln = "Mouse";
$e  = "mickey@disney.com";
$p  = "minnie";
$q = "INSERT INTO users (first_name, last_name, email, pass, registration_date) VALUES ('$fn', '$ln', '$e', SHA1('$p'), NOW() )";
$r = @mysqli_query ($dbc, $q); // Run the query.

if ($r) {
    echo "INSERTED Mickey, Mouse mickey@disney.com into users without validation.";
}

?>

</body>
</html>