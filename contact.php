<?php # Script 9.5 - register.php #2
// This script emails me the comments from the client.

$page_title = 'Contact';


 // Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        

    $errors = array(); // Initialize an error array.
    //Initialize values and strip for easy use
    $name = stripslashes($_POST['name']);
    $email = stripslashes($_POST['email']);
    $comments = stripslashes($_POST['comments']);
           
    // Check for a name:
    if (empty($_POST['name'])) {
        $errors[] = 'You forgot to enter your name.';
    } else {
        
    }
    
    // Check for an email address:
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
            if(!validEmail($email)){
                $errors[] = "Please enter a valid email.";
            }
        }
        
    //check for comments
    if(empty($_POST['comments'])){
        $errors[] = 'You forgot to enter your comments.';
    }
    

    

    

    
    if (empty($errors)) { // If everything's OK.
        
                
            // Send the email
            $to = "reiff.s@husky.neu.edu";
            $subject = "Jetpack Comments: $name $email";

            $headers = "From: $email";
            
            $message = "From: $name  at $email . Comments:  $comments";
 
            mail($to, $subject, $message, $headers);
            

            // Die with a success message
            die("<span class='success'>Success! Your message has been sent. We hope to get back to you as soon as possible.</span>");

    } else { // Report the errors.
    
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
        
    } // End of if (empty($errors)) IF.
    
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