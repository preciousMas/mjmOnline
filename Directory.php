<?php
//Configuration
$rootdir = 'folder_vault'; //root directory to manage files in
//Yes, that's all there is to configure... feel free to change things in the code though.
//Thanks to php.net for the following function... makes it work in PHP 4
if ( !function_exists('file_put_contents') && !defined('FILE_APPEND') ) {
	define('FILE_APPEND', 1);
	function file_put_contents($n, $d, $flag = false) {
	    $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
	    $f = @fopen($n, $mode);
	    if ($f === false) {
	        return 0;
	    } else {
	        if (is_array($d)) $d = implode($d);
	        $bytes_written = fwrite($f, $d);
	        fclose($f);
	        return $bytes_written;
	    }
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<style type="text/css">
body {
	font-family: Verdana, Arial, sans-serif;
	font-size: 14px;
}
table {
	border-collapse: collapse;
}
#filelist {
	background-color:#e0eeee;
}
#filelist th {
background-color:#39b7cd;
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
#footer {
    background-color:#50a6c2;
    color:white;
    clear:both;
    text-align:center;
   padding:5px;
   height:70px;
}
</style>
  <link rel="stylesheet" type="text/css" href="search.css" />
        
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
 
<div id="header">
<h1>MJM Online Admin Panel</h1>
</div><br/>

<div id="nav">
 <input type="button"  name="submit" class='tfbutton' onclick="location.href='index.php'" value="Logout"/>
       
  <br/>
         <br/><br/>      
  <input type="button"  name="submit" class='navbutton' width="50px" onclick="location.href='backup_database.php'" value="system backups"/><br/>
   <input type="button"  name="submit" class='navbutton' width="50px" onclick="location.href='database.php'" value="Manage Users"/><br/>
  <input type="button"  name="submit" class='navbutton' width="50px" onclick="location.href='database.php'" value="Manage Database"/><br/>
 
 </div>
   
    
    
    
<?php
error_reporting(E_ALL ^ E_NOTICE);
@$_GET['cd'] = str_replace('..', '', $_GET['cd']);
@$_GET['delete'] = str_replace('..', '', $_GET['delete']);
@$_GET['chmod'] = str_replace('..', '', $_GET['chmod']);
@$_GET['rename'] = str_replace('..', '', $_GET['rename']);
@$_GET['move'] = str_replace('..', '', $_GET['move']);
function error($text)
{
	echo '<div style="border: 1px dotted #000000; padding: 10px;">
	<strong>An error has occured:</strong><br />
	'.$text.'
	</div>';
	exit();
}
$dir = '';
if(isset($_GET['cd']))
{

	$dir .= '/'.$_GET['cd'];
}
$types = array(
	'c' => 'c.gif',
	'txt' => 'text.gif',
	'jpg' => 'img.png',
	'gif' => 'img.png',
	'png' => 'img.png',
	'bmp' => 'img.png',
	'html' => 'html.png',
	'avi' => 'video.png',
 	'exe' => 'binary.gig',
 	'tar' => 'tar.png',
 	'zip' => 'tar.png',
 	'doc' => 'wordprocessing.png',
 	'wav' => 'audio.png',
  	'mid' => 'audio.png',
  	'mp3' => 'audio.png',
  	'au' => 'audio.png',
  	'ogg' => 'sound2.gif',
  	'm4a' => 'sound2.gif',
  	'mp4' => 'sound2.gif',
  	'aac' => 'sound2.gif',
);
if(isset($_POST['upload']))
{
	$uploaddir = $rootdir.'/'.$_POST['dir'].'/';
	$uploadfile = $uploaddir . basename($_FILES['uploadedfile']['name']);
	
	if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $uploadfile))
	    echo "File successfully uploaded.\n<hr>";
	else
		echo 'There was an error uploading the file. Error code: '.$_FILES['uploadedfile']['error'];
	$dir = $_POST['dir'];
}
function recursive_delete($dir)
{
global $rootdir;
if(is_dir($rootdir.'/'.$_GET['dir'].'/'.$dir))
$dir_handle = opendir($rootdir.'/'.$_GET['dir'].'/'.$dir);
while($file = readdir($dir_handle))
{
if($file != "." && $file != "..")
{
if(is_dir($rootdir.'/'.$_GET['dir'].'/'.$dir."/".$file))
{
if(@!unlink ($rootdir.'/'.$_GET['dir'].'/'.$dir."/".$file))
error("Permission denied :<br/>To recursively delete a directory,CHMOD it to 777 .</br><a href=directory.php>Back to main page</a>" );

}
else recursive_delete($rootdir.'/'.$_GET['dir'].'/'.$dir);
}
}
closedir($dir_handle);
rmdir($rootdir.'/'.$_GET['dir'].'/'.$dir);
return true;
}
function recursive_chmod($dir, $value)
{
global $rootdir;
if(is_dir($rootdir.'/'.$_GET['dir'].'/'.$dir))
$dir_handle = opendir($rootdir.'/'.$_GET['dir'].'/'.$dir);
while($file = readdir($dir_handle))
{
if($file != "." && $file != "..")
{
if(!is_dir($rootdir.'/'.$_GET['dir'].'/'.$dir."/".$file))
{
chmod($rootdir.'/'.$_GET['dir'].'/'.$dir."/".$file, $value);
}
else recursive_chmod($rootdir.'/'.$_GET['dir'].'/'.$dir);
}
}
closedir($dir_handle);
chmod($rootdir.'/'.$_GET['dir'].'/'.$dir, $value);
return true;
}
if(!empty($_GET['create']))
{
	fopen($rootdir.'/'.$_GET['dir'].'/'.$_GET['create'], 'w+') or error('Could not create the file.</br><a href=directory.php>Back to main page</a> ');
	$dir = $_GET['dir'];
}
elseif(!empty($_GET['createdir']))
{
	mkdir($rootdir.'/'.$_GET['dir'].'/'.$_GET['createdir']) or error('Could not create the folder.</br><a href=directorye.php>Back to main page</a>');
	$dir = $_GET['dir'];
}
if(isset($_POST['filecontents']))
{
	if($_POST['cancel'] == 'Cancel')
	{
		$dir = $_POST['dir'];
	}
	else
	{
		file_put_contents($rootdir.'/'.$_POST['dir'].'/'.$_POST['file'], stripslashes($_POST['filecontents']));
		echo 'File saved successfully! If the new contents of the file do not appear, try clearing your web browser\'s cache.<br />
<a href="directory.php?dir='.$_POST['dir'].'">Back to File Manager</a>';
	}
}
elseif(isset($_GET['edit']) && $_GET['edit'] == 'WYSIWYG')
{
include('FCKeditor/fckeditor.php');
$filecontents = file_get_contents($rootdir.'/'.$_GET['dir'].'/'.$_GET['file']);
?>
<table width="70%" height="100%">
<tr>
<td valign="top" height="17px">
<form action="directory.php" method="POST">
<input type="hidden" name="file" value="<?php echo $_GET['file']; ?>">
<input type="hidden" name="dir" value="<?php echo $_GET['dir']; ?>">
<table width="100%"><tr><td>Editing: <?php echo $_GET['file'] ?></td><td style="text-align: right;"><input type="submit" name="cancel" value="Cancel" /> <input type="submit" value="Save" class="input"></td></tr></table>
</td>
</tr>
<tr>
<td valign="top">
<?php
$oFCKeditor = new FCKeditor('filecontents') ;
$oFCKeditor->BasePath = './FCKeditor/';
$oFCKeditor->Value = "$filecontents";
$oFCKeditor->Create() ;
?>
<br />
</form>
</td>
</tr>
</table>
<?php
}
elseif($_GET['delete'] == 'Delete')
{
	if(is_dir($rootdir.'/'.$_GET['dir'].'/'.$_GET['file']))
		recursive_delete($_GET['file']);	
	else
		@unlink($rootdir.'/'.$_GET['dir'].'/'.$_GET['file']) or error('Could not delete the file.<a href=directory.php>Back to main page</a>');
	$dir = $_GET['dir'];
}
elseif($_GET['submit'] == 'CHMOD')
     {
    $errorMsg="";
if(empty($_GET['chmod']))
    {
$errorMsg.='Could not move the file.<a href=directory.php>Back to main page</a>';
{
    

	if(is_dir($rootdir.'/'.$_GET['dir'].'/'.$_GET['file']))
	{
		@chmod($rootdir.'/'.$_GET['dir'].'/'.$_GET['file'], octdec($_GET['chmod'])) or error('Could not CHMOD the file.<a href=directory.php>Back to main page</a>');
	}
	else
	{
		@recursive_chmod($_GET['file'], octdec($_GET['chmod'])) or error('Could not CHMOD the file.<a href=directory.php>Back to main page</a>');
	}
	$dir = $_GET['dir'];
     }}}
elseif($_GET['submit'] == 'Rename')
{
	rename($rootdir.'/'.$_GET['dir'].'/'.$_GET['file'], $rootdir.'/'.$_GET['dir'].'/'.$_GET['rename']) or error('Could not rename the file.');
	$dir = $_GET['dir'];
}
elseif($_GET['submit'] == 'Move')
  {
	$_GET['dir'] = str_replace('//', '', $_GET['dir']);
	if($_GET['move'] == 'A-Z' && !empty($_GET['dir']) && $_GET['dir'] != '/')
		@rename($rootdir.'/'.$_GET['dir'].'/'.$_GET['file'], $rootdir.'/'.$_GET['dir'].'/../'.$_GET['file']) or error('Could not move the file. Make sure the directory exists.<a href=directory.php>Back to main page</a>');		
	elseif($_GET['move'] != 'A-Z')
		@rename($rootdir.'/'.$_GET['dir'].'/'.$_GET['file'], $rootdir.'/'.$_GET['dir'].'/'.$_GET['move'].'/'.$_GET['file']) or error('Could not move the file. Make sure the directory exists.<a href=directory.php>Back to main page</a>');
	$dir = $_GET['dir'];
     }

if(!isset($_GET['edit']))
{
	$handle = opendir($rootdir.'/'.$dir) or error('Error opening the specified directory.');
	$files = array();
	while($file = readdir($handle))
	{
		if($file != '.' && $file != '..')
			$files[] .= $file;
	}
	sort($files);
	$up1dir = substr($dir, 0, strrpos($dir, '/'));
	echo '<form action="directory.php">';
	if(str_replace('//', '', $dir) != '' && str_replace('//', '', $dir) != '/')
		echo '<a href="directory.php?dir='.$up1dir.'"><img src="images/up.png" alt="Up" width="25"/>Up 1 Directory</a> | <a href="directory.php"><img src="images/folder_home.png" alt="Home" width="25"/>Home</a><br><br>';
	echo '<table width="78%" border="1" id="filelist">
<tr><th>#</th><th>Select</th><th width="36">Icon</th><th>Name</th><th>Function</th><th>Permissions</th><th>Last Modified</th>';
	for($i = 0; $i< count($files); $i++)
	{
		$file = $files[$i];
		$type = substr($file, strrpos($file, '.') + 1, strlen($file));
		if(isset($types[$type]))
			$image = $types[$type];
		else
			$image = 'file.png';;
		$size = filesize($rootdir.'/'.$dir.'/'.$file);
		$perms = substr(sprintf('%o', fileperms($rootdir.'/'.$dir.'/'.$file)), -4);
		$mtime = date("F d Y H:i:s", filemtime($rootdir.'/'.$dir.'/'.$file));
		$filenum = $i + 1;
		if(is_dir($rootdir.'/'.$dir.'/'.$file))
			echo '<tr><td>'.$filenum.'</td><td><input type="radio" name="file" value="'.$file.'"/></td width="36"><td><img src="types/folder.png" alt="Type"/></td><td><a href="directory.php?cd='.$dir.'/'.$file.'">'.$file.'</a></td><td><input type="submit" class="delbutton" name="delete" value="Delete" onclick="return confirm("Are you sure yo want to delete?")" /></td><td>'.$perms.'</td><td>'.$mtime.'</td></tr>';
		else
			echo '<tr><td>'.$filenum.'</td><td><input type="radio" name="file" value="'.$file.'"/></td width="36"><td><img src="types/'.$image.'" alt="Type"/></td><td><a href="'.$rootdir.'/'.$dir.'/'.$file.'">'.$file.'</a></td><td><input type="submit" name="delete" value="Delete" class="delbutton"  onclick="return confirm("Are you sure yo want to delete?")" /></td><td>'.$perms.'</td><td>'.$mtime.'</td></tr>';
	}
?>
</table>
<br>
<table width="70%">
<tr>
<td>
Click a filename to view it. Click a directory name to enter it.

</td>
</tr>
</table>
<table>
<tr><td>CHMOD</td><td>
<input type="text" name="chmod" size="4" maxlength="4" onKeyPress="return disableEnterKey(event)"/> <input type="submit" name="submit" value="CHMOD"/></td></tr>
<tr><td>Rename</td><td><input type="text" name="rename" onKeyPress="return disableEnterKey(event)"/> <input type="submit" name="submit" value="Rename"/></td></tr>
<tr><td>Move</td><td><input type="text" name="move" value="Name of new directory" onKeyPress="return disableEnterKey(event)"/> <input type="submit" name="submit" value="Move"/><br>
<tr><td>New File</td><td><input type="text" name="create" onKeyPress="return disableEnterKey(event)"/> <input type="submit" value="Create"/></td></tr>
<tr><td>New Folder</td><td><input type="text" name="createdir" onKeyPress="return disableEnterKey(event)"/> <input type="submit" value="Create"/></td></tr>
</table >
</form>
<form enctype="multipart/form-data" action="directory.php" method="POST">
<table>
<tr><td>
<input type="hidden" name="MAX_FILE_SIZE" value="0" />
<input type="hidden" name="upload" value="1" />
<input type="hidden" name="dir" value="<?php echo $dir; ?>" />
Upload File</td><td><input name="uploadedfile" type="file" /> <input type="submit" value="Upload" />
</td>
</tr
</table>
</form>
<hr><br>
<?php
}
?>
      <div id="footer">
Copyright © created by Nthabiseng Masipa
</div>
</body>
</html>
