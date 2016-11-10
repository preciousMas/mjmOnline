
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

 <title>MJM Online</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
          <link rel="stylesheet" type="text/css" href="css/list.css" />
            <link rel="css/stylesheet" type="text/css" href="css/stylesheet.css" />


        </head>
    
        <body>
        <form method='POST' action=search_location.php>
        <input id='searchbox' name='searchbox'  type='textbox' />
           <input type="submit" name="submit" value="Search" onclick="ButtonClicked()">
</div>
<div id="buttonreplacement" style="margin-left:30px; display:none;">
<img src="images/loading.gif" alt="loading..."> searching...</span>     
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
</script></form>

<?php
$conn = mysql_connect ("localhost", "root", "") or die ('I cannot connect to the database because: ' . mysql_error()); 
$selected = mysql_select_db ("online") 
or die ("Could not select database"); 


// PHP Search Script 
$search=@$_POST['searchbox'];
$sql = "select * from file_location where File_number = '".@$_POST['searchbox']."'" ;
$result = mysql_query($sql,$conn)or die (mysql_error()); 

if (mysql_num_rows($result)==0){ 
echo "No Match Found"; 
}else{ 
while ($row = mysql_fetch_array($result)){ 



    	echo "<table style=\"font-family:arial;\">";	
                echo "<tr><td style=\"border-style:solid;border-width:/*-order-color:#98bf21;background:#98bf21;\">File_number</td><td style=\"border-style:solid;border-width:1px;border-color:#98bf21;background:#98bf21;\">Drawer_number</td><td style=\"border-style:solid;border-width:1px;border-color:#98bf21;background:#98bf21;\">File-Status</td></tr>";				             
echo "<tr><td style=\"border-style:solid;border-width:1px;border-color:#98bf21;\">";			
                echo $row['File_number'];
				echo "</td><td style=\"border-style:solid;border-width:1px;border-color:#98bf21;\">";
                echo $row['Drawer_number'];
				echo "</td><td style=\"border-style:solid;border-width:1px;border-color:#98bf21;\">";
                echo $row['File_status'];
				echo "</td></tr>";				
            }
				echo "</table>";
}              
mysql_close($conn); 
                
    