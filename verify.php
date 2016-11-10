
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>veryfy</title>
<link rel="stylesheet" href="style.css" type="text/css" />
<style>

*{
    padding: 0; /* Reset all padding to 0 */
    margin: 0; /* Reset all margin to 0 */
}
 
body{
    background: #F9F9F9; /* Set HTML background color */
    font: 14px "Lucida Grande";  /* Set global font size & family */
    color: #464646; /* Set global text color */
}
 
p{
    margin: 10px 0px 10px 0px; /* Add some padding to the top and bottom of the <p> tags */
}

#header{
    height: 45px; /* Set header height */
    background: #464646; /* Set header background color */
}
 
#header h3{
    color: #FFFFF3; /* Set header heading(top left title ) color */
    padding: 10px; /* Set padding, to center it within the header */
    font-weight: normal; /* Set font weight to normal, default it was set to bold */
}
 
#wrap{
    background: #FFFFFF; /* Set content background to white */
    width: 615px; /* Set the width of our content area */
    margin: 0 auto; /* Center our content in our browser */
    margin-top: 50px; /* Margin top to make some space between the header and the content */
    padding: 10px; /* Padding to make some more space for our text */
    border: 1px solid #DFDFDF; /* Small border for the finishing touch */
    text-align: center; /* Center our content text */
}
 
#wrap h3{
    font: italic 22px Georgia; /* Set font for our heading 2 that will be displayed in our wrap */
}
 
form{
    margin-top: 10px; /* Make some more distance away from the description text */
}
 
form .submit_button{
    background: #F9F9F9; /* Set button background */
    border: 1px solid #DFDFDF; /* Small border around our submit button */
    padding: 8px; /* Add some more space around our button text */
}
 
input{
    font: normal 16px Georgia; /* Set font for our input fields */
    border: 1px solid #DFDFDF; /* Small border around our input field */
    padding: 8px; /* Add some more space around our text */
}
</style>
</head>
<body>
    
    <div id="header">
        <h3>Mjm Online  >Account verification</h3>
    </div>
    <div id="wrap">
        
        <?php
         
            mysql_connect("localhost", "root", "") or die(mysql_error()); // Connect to database server(localhost) with username and password.
            mysql_select_db("Online") or die(mysql_error()); // Select registration database.
            
            if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
    $email = mysql_escape_string($_GET['email']); // Set email variable
    $hash = mysql_escape_string($_GET['hash']); // Set hash variable
                 
    $search = mysql_query("SELECT email, hash, active FROM users WHERE email='".$email."' AND Hash='".$hash."' AND Active='0'") or die(mysql_error()); 
    $match  = mysql_num_rows($search);
                 
    if($match > 0){
        // We have a match, activate the account
        mysql_query("UPDATE users SET active='1' WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error());
        echo '<div class="statusmsg">Your account has been activated, you can now login</div>';
    }else{
        // No match -> invalid url or account has already been activated.
        echo '<div class="statusmsg">The url is either invalid or you already have activated your account.</div>';
    }
                 
}else{
    // Invalid approach
    echo '<div class="statusmsg">Invalid approach, please use the link that has been send to your email.</div>';
}


             
        ?>
    </div>
    <div id="footer">
Copyright © created by Nthabiseng Masipa
</div>
  
</body>
</html>
