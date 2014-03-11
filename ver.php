<?php # Script 9.5 - register.php #2
// This script performs an INSERT query to add a record to the users table.

$page_title = 'Register';
include ('includes/header.html');

// Connect to MySQL.    
    require ('../../../../mysqli_connect.php'); //iPage
    //require ('../../../../../mysqli_connect.php');
    
// Select the database:
    $q = "USE mysite";    
    $r = @mysqli_query ($dbc, $q); 
echo "<p>connected to mysite</p>";

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
    $errors = array(); // Initialize an error array.
    
    // Check for a first name:
    if (empty($_POST['first_name'])) {
        $errors[] = 'You forgot to enter your first name.';
    } else {
        // $fn = trim($_POST['first_name']);
        $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    }
    
    // Check for a last name:
    if (empty($_POST['last_name'])) {
        $errors[] = 'You forgot to enter your last name.';
    } else {
        $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    }
    
    // Check for an email address:
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
        // Check that the email address is not already used:
        $q = "SELECT user_id FROM users WHERE email = '$e'";
        $r = @mysqli_query ($dbc, $q); // Run the query.
        $n = mysqli_num_rows($r);
        if ($n > 0) {
            //echo "<p>The email address $e is already in use.</p>";
            $errors[] = "The email address $e is already in use.";
        }
    }     
    
    // Check for a password and match against the confirmed password:
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Your password did not match the confirmed password.';
        } else {
            $p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
        }
    } else {
        $errors[] = 'You forgot to enter your password.';
    }
    
    if (empty($errors)) { // If everything's OK.
    
        // Register the user in the database...
        // Run the query.
        $q = "INSERT INTO users (first_name, last_name, email, pass, registration_date) VALUES ('$fn', '$ln', '$e', SHA1('$p'), NOW() )";        
        $r = @mysqli_query ($dbc, $q); 
        if ($r) { // If it ran OK.
        
            // Print a message:
            echo '<h1>Thank you!</h1>
        <p>You are now registered. In Chapter 12 you will actually be able to log in!</p><p><br /></p>';    
        
        } else { // If it did not run OK.
            
            // Public message:
            echo '<h1>System Error</h1>
            <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; 
            
            // Debugging message:
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
                        
        } // End of if ($r) IF.
        
        mysqli_close($dbc); // Close the database connection.

        // Include the footer and quit the script:
        include ('includes/footer.html'); 
        exit();
        
    } else { // Report the errors.
    
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
        
    } // End of if (empty($errors)) IF.
    
    mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>