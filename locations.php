<?php
	$hostname = "localhost";//host name
	$dbname = "online";//database name
	$username = "root";//username you use to login to php my admin
	$password = "";//password you use to login
	
	//CONNECTION OBJECT
	//This Keeps the Connection to the Databade
	$conn = new MySQLi($hostname, $username, $password, $dbname) or die('Can not connect to database')		
?>
<?php
include ('dbconnect.php');
//We check if the form has been sent
if(isset($_POST['submit']))
{
		
                		//We protect the variables
				$fileNumber = mysql_real_escape_string($_POST['fname']);
				$fileStatus = (@$_POST['Fstatus']);
				$fileDrawer = (@$_POST['Fdrawer']);
                                $Person =(@$_POST['Person']);
			
					//We save the informations to the databse
if(mysql_query("insert into file_location(File_number,Drawer_number,File_status,Person_responsible) values ('$fileNumber', '$fileStatus', '$fileDrawer','$Person')"))					
                              {
		
        ECHO "File location successfully stored";
       
	}
	else
	{
		
        ECHO'error while creating file location ...';
        
	}
}            
                                
?>
 <title>MJM Online</title>
        <link rel="stylesheet" type="text/css" href="search.css" />
          <link rel="stylesheet" type="text/css" href="css/list.css" />
          <style type="text/css">
body {
	font-family: Verdana, Arial, sans-serif;
	font-size: 14px;
}
table {
	border-collapse: collapse;
}
#filelist {
	background-color: #DDDDFF;
}
#filelist th {
background-color: #BBBBDD;
}
hr {
	color: black;
	background-color: black;
	border: 0;
	height:1px;
}
input {
	background-color: #DDDDDD;
	border: 1px solid black;
}
img {
	border: 0;
}
p::first-letter{
    font-size: 40px;
    color:#50a6c2;    
}
td{font-family: Arial;
font-size: 10pt;}
#border{
    border-bottom: 1px black solid;
}
#header {
    background:#50a6c2;
 color:#f9f9f9;
    text-align:center;
    padding:5px;
    height:70px;
}
#nav {
    line-height:30px;
    background-color:#c3dfef;
    height:600px;
    width:200px;
    float:left;
    padding:5px;	      
}
#section {
    
    background:#f9f9f9;
 font-family:"Courier New", Courier, monospace;
 height:300px;
    width:976px;
    float:left;
    padding:10px;	 	 
}
#footer {
    background-color:#50a6c2;
    color:white;
    clear:both;
    text-align:center;
   padding:5px;
   height:70px;
}
 #searchbox{
     padding:20px;
     float: right;
 }   
h1 {
    background:#00a2d1;
 color:#f9f9f9;
    text-align:center;
    padding:5px;
   
}
</style>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

        </head>
    <body>
        <?php
        include('search.php');
              function show_search_box()
{
	global $index, $search, $words, $search_mode, $this_file, $subdir, $icon_path;
       
        echo '<div id="tfheader">';
	echo"<form id='tfnewsearch' method='get' action='search.php'>
	<p><input type='text' class='tftextinput'  name='search' size='21' maxlength='20' placeholder='Enter search here'", htmlentities($search), "' />";
	if ($index != '' && strpos($index, '?') !== false)
	{
		$id_temp = explode('=', $index, 2);
		$id_temp[0] = substr(strstr($id_temp[0], '?'), 1);
		echo "<input type='hidden' name='$id_temp[0]' value='$id_temp[1]' />";
	}
	echo "\n<input type='hidden' name='dir' value='", translate_uri($subdir),
		"' /><select name='searchMode'>";
	$search_modes = array($words['files'] => 'f', $words['folders'] => 'd', $words['both'] => 'fd');
	foreach ($search_modes as $key => $element)
	{
		$sel = (($search_mode == $element) ? ' selected="selected"' : '');
		echo "\t<option$sel value='$element'>$key</option>\n";
	}
	echo "</select><input type='submit' class='tfbutton' value='Search",
		'\' class="button" /></p></form></td></tr></table>';
       echo' <div class="tfclear"></div>';
        echo '</div></div>';
}

?>      
 <div id="nav">
  <input type="button" class='tfbutton'  name="submit" onclick="location.href='index.php'" value="Logout"/>
<input type="button" class='tfbutton'  name="submit" onclick="location.href='create.php'" value="Prev"/><br/>
  <br/>                      

  <input type="button"  name="submit" class='navbutton' onclick="location.href='admin.php'" value="Page Admin login"/><br/>
  <input type="button"  name="submit" class='navbutton' onclick="location.href='create.php'" value="Manage files/folders"/><br/>
  <input type="button"  name="submit" class='navbutton' onclick="location.href='locations.php'" value="File physical location"/><br/>
  
</div>
    
         
  
  
     <br/>
     
      <table width="82%" align ="center">
<div id="border">
  <p>Create File physical location</p>
</div>

 <table border="0" cellspacing="20">
<tr>     
<form id="loginForm" name="loginForm" method="post" action="locations.php" aliign=center">
<td>File number:</td> <td><input name="fname" type="text" class="textfield" id="fname" />
    </td>
</tr>       
<tr>
<td>File status:</td> <td><select name="Fstatus">
           <option>--select--</option>
           <option>Outstanding documents</option>
           <option>Advocate</option>
           <option>Finance_clerk</option>
           <option>Attorney</option><br/>  
                    
      </select></td>
</tr>           
       
 <tr>
<td>Person Responsible:</td><td><select name="Fdrawer">
           <option>--select--</option>
           <option>Ms Ledwaba</option>
           <option>Mr Moruwe</option>
           <option>Mrs Jacobs</option>
   </select>
    </td>
</tr>
      
  <tr>    
      <td>drawer number:</td><td> <select name="Person">
         <option>--select--</option>
           <option>Room2 A-D</option>
           <option>Room2 E-H</option>
           <option>Room2 I-L</option>
           <option>Room3 M-Q</option>
           <option>Room3 R-U</option>
           <option>Room3 V-7</option><br/>
 </select></td></tr>
<tr>    
    <td>Date created :</td><td><input type="date" name="Filecreate"></td></td>
</tr>
 
<p><div id="formsubmitbutton"></p>
<tr><td><input type="submit" class='tfbutton' name="submit" value="Save " onclick="ButtonClicked()">
</div></table>
<?php
//Create a query
@$Fileid = $_POST['fileId'];   
$sql = "SELECT * FROM file_location WHERE File_number = '".$Fileid."'";
//submit the query and capture the result
$result = $conn->query($sql) or die(mysql_error());
$query=getenv('QUERY_STRING');
parse_str($query);

?>

<table border="0" cellspacing="0" width="82%">
<div id="border">
  <p>Update/edit file physical locations</p>
</div>

<h2>Update Record <?php echo $Fileid;?></h2>
<form action="" method="post">
<?php
		while ($row = $result->fetch_assoc()) {?>
 <tr>
    <td>File number to update:</td> <td><input type="text" name="fileId" value="<?php echo $row['File_number']; ?>"><INPUT TYPE="Submit" VALUE="retrieve" NAME="Submit"></td>
</tr>    
<tr>
<td>Rename file:</td> <td><input type="text" readonly="true" name="updateFileName" value="<?php echo $row['File_number']; ?>"></td>
</tr>    
<tr>    
<td>Old drawer number:</td> <td><input type="text" readonly="true"  name="updatetitle" value="<?php echo $row['Drawer_number']; ?>">
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
           <option>Active files</option>
           <option>Review</option>
           <option>Out standing documents</option>
           <option>Closed files</option><br/>  
                    
      </select></td>
</tr>    
<tr>       

<tr>
<td>Person Responsible:</td> <td><input type="text"readonly="true"  name="updatepubdate" value="<?php echo $row['Person_responsible']; ?>"></td>
</tr>

<tr>
  <p><div id="formsubmitbutton">   
<td><input type="submit" name="submit" class='tfbutton' value="Update" onclick="ButtonClicked()"></td><br/>
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
 </form>   
 
<?php	}
	?>

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

       </div></table>
       <div id="footer">
Copyright © created by Nthabiseng Masipa
</div>
</body>
</html>
    

