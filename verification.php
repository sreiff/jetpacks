<!doctype html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Savannah Reiff</title>
    <link rel="stylesheet" href="style.css">
    <style type="text/css"></style>
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    
</head>

<body>
    

    
    <p>
        
    
        

<?php
// Clean up the input values
foreach($_POST as $key => $value) {
  if(ini_get('magic_quotes_gpc'))
    $_POST[$key] = stripslashes($_POST[$key]);
 
  $_POST[$key] = htmlspecialchars(strip_tags($_POST[$key]));
}
 
// Assign the input values to variables for easy reference
$name = $_POST["name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$location = $_POST["location"];
$time = $_POST["time"];
$date = $_POST["date"];
$month = $_POST["month"];
$year = $_POST["year"];
$quantity = $_POST["quantity"]; 
$comments = $_POST["comments"];
$start_time = $_POST["start_time"];
 
// Test input values for errors
$errors = array();
if(strlen($name) < 2) {
  if(!$name) {
    $errors[] = "You must enter a name.";
  } else {
    $errors[] = "Name must be at least 2 characters.";
  }
}
if(!$email) {
  $errors[] = "You must enter an email.";
} else if(!validEmail($email)) {
  $errors[] = "You must enter a valid email.";
}
if(!$phone) {
  $errors[] = "You must enter a phone number.";
} else if(!validPhone($phone)) {
  $errors[] = "You must enter a valid phone number.";
}
if(!$time) {
  $errors[] = "You must enter a time.";
} 
if(!$date) {
  $errors[] = "You must enter a date.";
}
if(!$quantity) {
  $errors[] = "You must enter the number of riders";
} 

 
if($errors) {
  // Output errors and die with a failure message
  $errortext = "";
  foreach($errors as $error) {
    $errortext .= "<p>".$error."</p>";
  }
  die("<span class='failure'>The following errors occured. Please fix them and resubmit:<p>". $errortext ."</p></span>");
}
else{
        ?>
        Thank you, <?php echo $_POST["name"]; ?><br>
        Here is your confirmation information: <br> <br>
        Your email address is: <?php echo $_POST["email"]; ?> <br>
        Your phone number is: <?php echo $_POST["phone"]; ?> <br>
        You selected: <?php echo $_POST["location"]; ?> <br>
        You will be riding for: <?php echo $_POST["time"]; ?> <br>
        You are scheduled for: <?php echo $_POST["start_time"]; ?> on <?php echo $_POST["month"]; ?> <?php echo $_POST["date"]; ?> <?php echo $_POST["year"]; ?> <br>
        There are <?php echo $_POST["quantity"]; ?> people in your party. <br>
        Thank you for your comments: <?php echo $_POST["comments"]; ?> <br> <br>
        <?php
        // Send the email
            $to = $email;
            $subject = "Jetpack Confirmation: $name";

            $headers = "From: reiff.s@husky.neu.edu";
            
            $message = "Thank you, $name for your reservation. You are all set to go on $month $date , $year at $start_time for $quantity person(s). See you soon in $location .";
 
            mail($to, $subject, $message, $headers);
 
        // Die with a success message
            die("<span class='success'>Success! Your message has been sent.</span>");   
}


 
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

//code from http://plasticbrain.net/resources/php-validate-email-address-and-phone-number/
function validPhone($phone)  
{  
  if(preg_match("/^([1]-)?[0-9]{3}[0-9]{3}[0-9]{4}$/i", $phone))
        {  
      return true;  
        }  
      else  
        {   
        return false;  
        }  
}  

?>   
 </p>
    
     
   
    
</body>
</html>
