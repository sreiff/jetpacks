<?php # Script 9.5 - register.php #2
// This script performs an INSERT query to add a record to the users table.

$page_title = 'Register';


// Connect to MySQL.    
    require ('../mysqli_connect.php');  //iPage
    //require ('../../../../../mysqli_connect.php');
    
// Select the database:
    $q = "USE jetpack";    
    $r = @mysqli_query ($dbc, $q); 
//echo "<p>connected to mysite</p>";

 // Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
    $errors = array(); // Initialize an error array.
        
// Assign the input values to variables for easy reference

    
    // Check for a name:
    if (empty($_POST['name'])) {
        $errors[] = 'You forgot to enter your name.';
    } else {
        $fn = strip_tags($_POST['name']);
        $name = mysqli_real_escape_string($dbc, trim($fn));
    }
    
    // Check for an email address:
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            if(!validEmail($email)){
                $errors[] = "Please enter a valid email.";
            }
        }
        
   // Check for a name:
    if (empty($_POST['pass'])) {
        $errors[] = 'You forgot to enter your password.';
    } else {
        $p = strip_tags($_POST['pass']);
        $pass = mysqli_real_escape_string($dbc, trim($p));
    }
        
    }
    

    

    
    if (empty($errors)) { // If everything's OK.
        
        // Check that the email address is not already used:
        $q = "SELECT user_id FROM users WHERE email = '$email'";
        $r = @mysqli_query ($dbc, $q); // Run the query.
        $n = mysqli_num_rows($r);
        if ($n > 0) {
            echo "<p>The email address $e is already in use.</p>";
            
            }
        else{
            // Register the user in the database...
        // Run the query.
        $q2 = "INSERT INTO reservation (location, length, start_time, month, day, year, quantity, email) VALUES ('$loc', '$time', '$start_time', '$month', '$day', '$year', '$quantity', '$email')";   
        $k = @mysqli_query ($dbc, $q2);
        if ($k) { // If it ran OK.

            // Print a message:
            echo '<h1>Thank you!</h1>
        <p>You are now registered.</p><p><br /></p>';  
        
        // Die with a success message
            die("<span class='success'>Success! Your message has been sent.</span>");   
        }
          
        
         else { // If it did not run OK.
            
            // Public message:
            echo '<h1>System Error</h1>
            <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; 
            
            // Debugging message:
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q1 . '</p>';
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q2 . '</p>';
                        
        } // End of if ($r) IF.
        
        mysqli_close($dbc); // Close the database connection.

        exit();
        
    } else { // Report the errors.
    
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
    }   
    } // End of if (empty($errors)) IF.
    
    
    mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.


// A function that checks to see if
// an email is valid
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
    }
?>