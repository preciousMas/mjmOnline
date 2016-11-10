<?php
include_once 'dbconnect.php';

if(isset($_SESSION['user'])!="")
{
	header("Location: directory.php");
}

if(isset($_POST['btn-login']))
{
	$email = mysql_real_escape_string($_POST['email']);
	$upass = mysql_real_escape_string($_POST['pass']);
        
        $res=mysql_query("SELECT * FROM users WHERE email='$email'");
	$row=mysql_fetch_array($res);
	
	if($row['password']==md5($upass))
	{
		$_SESSION['user'] = $row['user_id'];
		header("Location: directory.php");
	}
	else
	{
		
        echo'wrong details';
}}  

?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

 <title>MJM Online</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        </head>
    
        <body>
     <style>
   .register-form{
    width: 500px;
    margin: 0 auto;
    text-align: center;
    padding: 10px;
    color: #fff;
    background :#50a6c2 ;
    border-radius: 10px;
    -webkit-border-radius:10px;

    -moz-border-radius:10px;
}

.register-form form input{padding: 5px;}
.register-form .btn{background: #726E6E;
padding: 7px;
border-radius: 5px;
text-decoration: none;
width: 80px;
display: inline-block;
color: #FFF;}

.register-form .register{
border: 0;
width: 60px;
padding: 8px;
}
 
    
</style>
</head>
<br/>
<br>
    <br>
        
    </br>
    <center>
<div class="register-form">
<h1>Login</h1>

<form action="" method="POST">
    <center>
        <p>Username=" root"</p>
        <p>password="1"</p>
    <p><label>User Name : </label>
    <input type="text" name="email" placeholder="username" required  /></p>
    <p><label>Password&nbsp;&nbsp; : </label>
     <input id="password" type="password" name="pass" placeholder="password" required  /></p>
   
  

<td>    <div id="formsubmitbutton">
<input class="btn register" type="submit" name="btn-login" value="Login" onclick="ButtonClicked()"><div id="buttonreplacement" style="margin-left:30px; display:none;">
    <img src="images/load.gif" height="30" alt="loading..."> Please wait...</span>     
</div>
</div>
    </center>
<script type="text/javascript">

function ButtonClicked()
{
   document.getElementById("formsubmitbutton").style.display = ""; // to undisplay
   document.getElementById("buttonreplacement").style.display = ""; // to display
   return true;
}
var FirstLoading = true;
function RestoreSubmitButton()
{
   if( FirstLoading )
   {
      FirstLoading = false;
      return;
   }
   document.getElementById("formsubmitbutton").style.display = ""; // to display
   document.getElementById("buttonreplacement").style.display = "none"; // to undisplay
}
// To disable restoring submit button, disable or delete next line.
document.onfocus = RestoreSubmitButton;
</script>

</form>
</div>
</center>
    <div id="footer">

</div>
    
    </body>
</html>
