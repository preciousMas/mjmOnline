<?php
	$hostname = "localhost";//host name
	$dbname = "online";//database name
	$username = "root";//username you use to login to php my admin
	$password = "";//password you use to login
	
	//CONNECTION OBJECT
	//This Keeps the Connection to the Databade
	$conn = new MySQLi($hostname, $username, $password, $dbname) or die('Can not connect to database')		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

 <title>MJM Online</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
          <link rel="stylesheet" type="text/css" href="css/list.css" />
        </head>
    
        <body>
     <div id=container>
            <div id="header">
                 <img src="images/banner.jpg" alt="company log" width="900" height="100" style=padding-top:5px;>
            <?php include("nav.html");?>
                </div>
    
      <br/>
          
          <center>         
    <fieldset>
         <legend>Create File physical location</legend> 
 <?php
//Create a query
@$Fileid = $_POST['fileId'];   
$sql = "SELECT * FROM file_location WHERE File_number = '".$Fileid."'";
//submit the query and capture the result
$result = $conn->query($sql) or die(mysql_error());
$query=getenv('QUERY_STRING');
parse_str($query);
//$ud_title = $_POST['Title'];
//$ud_pub = $_POST['Publisher'];
//$ud_pubdate = $_POST['PublishDate'];
//$ud_img = $_POST['Image'];

?>
<h2>Update Record <?php echo $Fileid;?></h2>
<form action="" method="post">
<?php
	
	
	while ($row = $result->fetch_assoc()) {?>
    
<table border="0" cellspacing="0" width="100%">
<tr>
<td>File number to update:</td> <td><input type="text" name="fileId" value="<?php echo $row['File_number']; ?>"><INPUT TYPE="Submit" VALUE="find" NAME="Submit"></td>
</tr>    
<tr>
<td>Rename file:</td> <td><input type="text" name="updateFileName" value="<?php echo $row['File_number']; ?>"></td>
</tr>    
<tr>    
<td>Old drawer number:</td> <td><input type="text" name="updatetitle" value="<?php echo $row['Drawer_number']; ?>">
     <select name="Person">
         <option>--select--</option>
           <option>Room2 A-D</option>
           <option>Room2 E-H</option>
           <option>Room2 I-L</option>
           <option>Room3 M-Q</option>
           <option>Room3 R-U</option>
           <option>Room3 V-7</option><br/>
 </select></td>
</tr>
<tr>
<td>Old file status:</td> <td><input type="text" name="updateFileName" value="<?php echo $row['File_status']; ?>"><select name="Fstatus">
           <option>--select--</option>
           <option>Outstanding documents</option>
           <option>Advocate</option>
           <option>Finance_clerk</option>
           <option>Attorney</option><br/>  
                    
      </select></td>
</tr>    
<tr>       

<tr>
<td>Person Responsible:</td> <td><input type="text" name="updatepubdate" value="<?php echo $row['Person_responsible']; ?>"></td>
</tr>

<tr>
  <p><div id="formsubmitbutton">   
<td><input type="submit" name="submit" value="Start Upload" onclick="ButtonClicked()"></td>
</tr>
   </div>
<div id="buttonreplacement" style="margin-left:30px; display:none;">
    <img src="images/loading.gif" alt="loading...">   
</div>

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
    
    
</table>
<?php	}
	?>
</form>
<?php

        if(isset($_POST['Submitfind']))
	if(isset($_POST['Submit'])){//if the submit button is clicked
	
         
	$update = $_POST['updateFileName'];
        $Rename=$_POST['File_number'];
        $updaeLocation=$_POST['Drawer_number'];
        $updateStatus=$_Post['File_status'];
	
	$query="UPDATE file_location SET File_number=$update where File_number = '".$Fileid."'";
	//$query = "UPDATE Books WHERE BookID = '".$bookid."'";//update the database query
	mysql_query($query) or die("Cannot update");//update or error
	}
?>
</body>
</html>
      
       
    </body>
</html>
