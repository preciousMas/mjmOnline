 <?php
 
 
include_once 'dbconfig.php';
if(isset($_POST['btn-save']))
{
 // variables for input data
 $first_name = $_POST['first_name'];
 $last_name = $_POST['last_name'];
 $Email = $_POST['email'];
 $Gender = $_POST['gender'];
 $Occupation = $_POST['occupation'];
 
 // variables for input data

 //generate password
 function random_password($length=6){
     $char="ABCDEFGHIJKLMNOPabcdefghijklmnopqrstuvwxyz0123456789@$#";
     $password=  substr(str_shuffle($char),0,$length);
     return $password;
 }
 $password=  random_password(6);
 
 $hash=md5(rand(0,1000));
 // sql query for inserting data into database
 $sql_query = "INSERT INTO users(first_name,last_name,Email,Gender,Occupation,Password,Hash,Active) VALUES('$first_name','$last_name','$Email','$Gender','$Occupation','$password','$hash')";
 // sql query for inserting data into database
 
 // sql query execution function
 if(mysql_query($sql_query))
 {
  ?>
  <script type="text/javascript">
  alert('User data Inserted Successfully ');
  window.location.href='index.php';
  </script>
  <?php
 }
 else
 {
  ?>
  <script type="text/javascript">
  alert('An error occured while inserting data');
  </script>
  <?php
 }
 
 // if all is well we mail off a little thank you email. We know it is
        // safe to do so because we have validated the email address.
           $to =$email;
            $subject = 'Account created';
            $msg= 'Dear '.$first_name.''.$last_name.'\n
                Yout account has been created you can login with the following details \n
                -------------------------------------------------\n
                Username: '.$Email.' \n
                Password: '.$password.'\n
                --------------------------------------------------\n    
              Please click the link to activate your account:
              http://www.mjmOnline.com/verify.php?email='.$email.'&Hash='.$hash.' ';
                    
                $headers='From:noreply@mjmOnline.com' ."\r\n";
                mail($to,$subject, $msg,$headers);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MJM Online Admin Panel</title>
<link rel="stylesheet" href="style_1.css" type="text/css" />
 <link rel="stylesheet" type="text/css" href="search.css" />  
     
<style>
    #header {
    background:#00a2d1;
 color:#f9f9f9;
    text-align:center;
    padding:5px;
}
#nav {
    line-height:30px;
    background-color:#c3dfef;
    height:515px;
    width:200px;
    float:left;
    padding:5px;	      
}

#footer {
    background-color:#50a6c2;
    color:white;
    clear:both;
    text-align:center;
   padding:70px;	 	 
}

</style>
</head>
<body>

<div id="header">
 
    <label>MJM Online Admin Panel</label>
    </div>

<div id="nav">
 <input type="button"  name="submit" class='tfbutton' onclick="location.href='index.php'" value="Logout"/>
       
  <input type="button"  name="submit" class='tfbutton' onclick="location.href='.database.php'" value="Next"/><br/>
         <br/><br/>       
      <br/>     
 <input type="button"  name="submit" class='navbutton' onclick="location.href='backup_database.php'" value="system backups"/><br/>
 <input type="button"  name="submit" class='navbutton' onclick="location.href='database.php'" value="Manage Database"/><br/>
 <input type="button"  name="submit" class='navbutton' onclick="location.href='directory.php'" value="Manage Directory"/><br/>
 
</div>  
    <form method="post">
    <table align="left">
    <tr>
    <td align="center"><a href="index.php">back to main page</a></td>
    </tr>
    <tr>
    <td><input type="text" name="first_name" placeholder="First Name" required /></td>
    </tr>
    <tr>
    <td><input type="text" name="last_name" placeholder="Last Name" required /></td>
    </tr>
    <tr>
    <td><input type="text" name="email" placeholder="Email" required /></td>
    </tr>
    <tr>
   <td>
	   <select name="gender" />
            <option>Gender</option>
           <option>male</option>
           <option>female</option>
           
</select></td></tr>
    <tr>      
        <td> 
	   <select name="occupation" />
           <option>Occupation</option>
           <option>admin_clerk</option>
           <option>Advocate</option>
           <option>Finance_clerk</option>
           <option>Attorney</option> 
           </select></td></tr>

    <tr>
    <td><input type="text" name="Password" placeholder="password" required /></td>
    </tr>
    <tr>    
    <td><button class='tfbutton' type="submit" name="btn-save"><strong>SAVE</strong></button></td>
    </tr>
    </table>
    </form>
    </div>
</div>
<div id="footer">
Copyright © created by Nthabiseng Masipa
</div>

</body>
</html>

