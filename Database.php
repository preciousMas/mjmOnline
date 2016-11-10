

<?php
include_once 'dbconfig.php';

// delete condition
if(isset($_GET['delete_id']))
{
 $sql_query="DELETE FROM users WHERE user_id=".$_GET['delete_id'];
 mysql_query($sql_query);
 header("Location: $_SERVER[PHP_SELF]");
}
// delete condition
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mjm online files management system</title>
  <link rel="stylesheet" type="text/css" href="search.css" />
        
<link rel="stylesheet" href="style_1.css" type="text/css" />
<style>
    #header {
    background:#50a6c2;
 color:#f9f9f9;
    text-align:center;
    padding:5px;
}
#nav {
    line-height:30px;
    background-color:#c3dfef;
    height:400px;
    width:250px;
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
    
</style>
<script type="text/javascript">
function edt_id(id)
{
 if(confirm('Sure to edit ?'))
 {
  window.location.href='edit_data.php?edit_id='+id;
 }
}
function delete_id(id)
{
 if(confirm('Sure to Delete ?'))
 {
  window.location.href='index.php?delete_id='+id;
 }
}
</script>
</head>
<body>

   <div id="header">
<h1>MJM Online Admin Panel</h1>
</div> 
 
<div id="nav">
    
<input type="button"  name="submit" class="tfbutton" onclick="location.href='index.php'" value="Logout"/>
       
  <br/>
          <br/>
  <input type="button"  name="submit" class='navbutton' onclick="location.href='backup_database.php'" value="system backups"/><br/>
  <input type="button"  name="submit" class='navbutton' onclick="location.href='database.php'" value="Manage Users"/><br/>
  <input type="button"  name="submit" class='navbutton' onclick="location.href='directory.php'" value="Manage Directory"/><br/>
  
</div>       
         
     <table align="left">
    <tr>
    <th colspan="6"><a href="add_data.php">add data here.</a></th>
    </tr>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Email</th>
    <th>Gender</th>
    <th>Occupation</th>
    
    <th colspan="2">Operations</th>
    </tr>
    <?php
 $sql_query="SELECT * FROM users";
 $result_set=mysql_query($sql_query);
 while($row=mysql_fetch_row($result_set))
 {
  ?>
        <tr>
        <td><?php echo $row[1]; ?></td>
        <td><?php echo $row[2]; ?></td>
        <td><?php echo $row[3]; ?></td>
        <td align="center"><a href="javascript:edt_id('<?php echo $row[0]; ?>')"><p> Edit</p></a></td>
        <td align="center"><a href="javascript:delete_id('<?php echo $row[0]; ?>')"><p> Delete</p></a></td></a></td>
        </tr>
        <?php
 }
 ?>
    </table>
    </div>
</div>

</center>
    <div id="footer">
Copyright © created by Nthabiseng Masipa
</div>
</body>
</html>

