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
    $loc = $_POST["location"];
    $month = $_POST["month"];
    $year = $_POST["year"];
    $start_time = $_POST["start_time"];
    
    // Check for a name:
    if (empty($_POST['name'])) {
        $errors[] = 'You forgot to enter your name.';
    } else {
        $fn = stripslashes($_POST['name']);
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
    
    
    // Check for a phone number:
    if (empty($_POST['phone'])) {
        $errors[] = 'You forgot to enter your phone number.';
    } else {
        $phone = trim($_POST['phone']);
        if(!preg_match("/^([1]-)?[0-9]{10}$/i", $phone)){
            $errors[] = "Please enter a valid phone number.";
        }
    }
    
    //Check for an amount of time (30 or 60)
    if(empty($_POST['time'])) {
        $errors[] = "You must enter a time.";
    } else {
        $time = mysqli_real_escape_string($dbc, trim($_POST['time']));
        if($time = '30 minutes'){
            $time = 1;
        }else {
            $time =2;
        }
    }
    
    //Check for day
    if(empty($_POST['date'])) {
        $errors[] = "You must enter a day.";
    } else {
        $day = mysqli_real_escape_string($dbc, trim($_POST['date']));
        
    }
    
    //Check for number of riders
    if(empty($_POST['quantity'])) {
        $errors[] = "You must enter the number of riders.";
    } else {
        $quantity = mysqli_real_escape_string($dbc, trim($_POST['quantity']));
        // Check that the email address is not already used:
        $q = "SELECT sum(quantity) FROM reservation WHERE day = '$day' && month = '$month' && year = '$year' && location = '$loc' && start_time = '$start_time'";
        //echo "<p>I am here</p>";
        $r = @mysqli_query ($dbc, $q); // Run the query.
        $row = mysqli_fetch_row($r);
        $sum = $row[0];
        //echo $r;
        if ($sum + $quantity > 6) {
            $errors[] = "I'm sorry, we do not have $quantity openings for $month $day , $year at $start_time please try another time.";
        }
        
    }
    

    

    
    if (empty($errors)) { // If everything's OK.
        
        // Check that the email address is not already used:
        $q = "SELECT user_id FROM clients WHERE email = '$email'";
        $r = @mysqli_query ($dbc, $q); // Run the query.
        $n = mysqli_num_rows($r);
        if ($n > 0) {
            //echo "<p>The email address $e is already in use.</p>";
            $q2 = "INSERT INTO reservation (location, length, start_time, month, day, year, quantity, email) VALUES ('$loc', '$time', '$start_time', '$month', '$day', '$year', '$quantity', '$email')";
            $r2 = @mysqli_query ($dbc, $q2);
            if ($r2){
                
            // Send the email
            $to = $email;
            $subject = "Jetpack Confirmation: $name";

            $headers = "From: reiff.s@husky.neu.edu";
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            //begin of HTML message 
            $message = "
            <html>
            <body>
            <p>
            Thank you, <b> $name </b> for your reservation. You are all set to go on <b> $month $day , $year </b> at <b>$start_time</b> for <b>$quantity person(s)</b>. See you soon in $loc.
            <br>
            <br>
            Best,
            <br>
            Jet Pack Rentals
            </body> 
            </html> 
            ";
            
            // use wordwrap() if lines are longer than 70 characters
            $message = wordwrap($message,70);       
 
 
            mail($to, $subject, $message, $headers);
            
                // Print a message:
            echo '<h1>Welcome Back!</h1>
            <p>You are now registered.</p><p><br /></p>';
            // Die with a success message
            die("<span class='success'>Success! Your message has been sent.</span>");
            
            }else { // If it did not run OK.
            
            // Public message:
            echo '<h1>System Error</h1>
            <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; 
            
            // Debugging message:
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q1 . '</p>';
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q2 . '</p>';
                        
        }
        }else{
            // Register the user in the database...
        // Run the query.
        $q1 = "INSERT INTO clients (name, email, phone, registration_date) VALUES ('$name', '$email', '$phone', NOW() )";        
        $q2 = "INSERT INTO reservation (location, length, start_time, month, day, year, quantity, email) VALUES ('$loc', '$time', '$start_time', '$month', '$day', '$year', '$quantity', '$email')";   
        $r = @mysqli_query ($dbc, $q1);
        $k = @mysqli_query ($dbc, $q2);
        if ($r && $k) { // If it ran OK.
            // Send the email
            $to = $email;
            $subject = "Jetpack Confirmation: $name";

            $headers = "From: reiff.s@husky.neu.edu";
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            //begin of HTML message 
            $message = "
            <html>
            <body>
            <p>
            Thank you, <b> $name </b> for your reservation. You are all set to go on <b> $month $day , $year </b> at <b>$start_time</b> for <b>$quantity person(s)</b>. See you soon in $loc.
            <br>
            <br>
            Best,
            <br>
            Jet Pack Rentals
            </body> 
            </html> 
            ";
            // use wordwrap() if lines are longer than 70 characters
            $message = wordwrap($message,70);   
            
            mail($to, $subject, $message, $headers);
    
        
        
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
        }
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