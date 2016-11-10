


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/list.css" /> 
  <title>MJM Online</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
          <link rel="stylesheet" type="text/css" href="search.css" />
          <link rel="css/stylesheet" type="text/css" href="style.css" />

        </head>
    
<style type="text/css">
    
body {
	font-family: Verdana, Arial, sans-serif;
	font-size: 14px;
}
table {
	border-collapse: collapse;
}
#filelist {
	background-color:#e0eeee ;
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
#bottom-right{
   position:absolute;
   bottom:0px;
   right:600px;
    
}
h1 {
    text-align:center;
    padding:5px;
    
}
#nav {
    line-height:30px;
    background-color: #c3dfef;
    height:450px;
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
#searchbox{
     position:relative;
     top:0px;
     float:right;
 }
 
</style>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
   <div id="tfheader">
            <form id="tfnewsearch" method="get" action="search_location.php">
		      <input type="text" class="tftextinput" placeholder="location search" name='searchbox'  size="21" maxlength="120">
                            <input type="submit" class='tfbutton' name="submit" value="search" class="tfbutton">
		</form>
	<div class="tfclear"></div>
	</div><br/>
                </h1>   </div><br/>
                  
<div id="nav">
  <input type="button"  name="submit" class='tfbutton' onclick="location.href='index.php'" value="Logout"/>
  <input type="button"  name="submit" class='tfbutton' onclick="location.href='locations.php'" value="Next"/><br/> <br/><br/>
  <input type="button"  name="submit" class='navbutton' onclick="location.href='admin.php'" value="Page Admin login"/><br/>
  <input type="button"  name="submit" class='navbutton' onclick="location.href='create.php'" value="Management files/folders"/><br/>
  <input type="button"  name="submit" class='navbutton' onclick="location.href='locations.php'" value="File physical location"/><br/>


</div>
  
    
<?php


$rootdir = 'folder_vault'; //root directory to manage files in
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
	'exe' => 'binary.gif',
 	'tar' => 'zip.jpg',
 	'zip' => 'zip.jpg',
 	'doc' => 'word.png',
        'pdf'=>  'pdf.gif',
  	'mp3' => 'sound2.gif',
  	'au' => 'sound2.gif',
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
error("Permission denied :<br/>To recursively delete a directory,CHMOD it to 777 .</br><a href=create.php>Back to main page</a>" );

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
	fopen($rootdir.'/'.$_GET['dir'].'/'.$_GET['create'], 'w+') or error('Could not create the file.</br><a href=create.php>Back to main page</a> ');
	$dir = $_GET['dir'];
}
elseif(!empty($_GET['createdir']))
{
	@mkdir($rootdir.'/'.$_GET['dir'].'/'.$_GET['createdir']) or error('Could not create the folder.</br><a href=create.php>Back to main page</a>');
	$dir = $_GET['dir'];
}

if($_GET['delete'] == 'Delete')
{
	if(is_dir($rootdir.'/'.$_GET['dir'].'/'.$_GET['file']))
		recursive_delete($_GET['file']);	
	else
		@unlink($rootdir.'/'.$_GET['dir'].'/'.$_GET['file']) or error('Could not delete the file.<a href=CREATE.php>Back to main page</a>');
	$dir = $_GET['dir'];
}
elseif($_GET['submit'] == 'Rename')
{
	rename($rootdir.'/'.$_GET['dir'].'/'.$_GET['file'], $rootdir.'/'.$_GET['dir'].'/'.$_GET['rename']) or error('Could not rename the file.</br><a href=create.php>Back to main page</a>" );
');
	$dir = $_GET['dir'];
}
elseif($_GET['submit'] == 'Move')
  {
	$_GET['dir'] = str_replace('//', '', $_GET['dir']);
	if($_GET['move'] == 'A-Z' && !empty($_GET['dir']) && $_GET['dir'] != '/')
		@rename($rootdir.'/'.$_GET['dir'].'/'.$_GET['file'], $rootdir.'/'.$_GET['dir'].'/../'.$_GET['file']) or error('Could not move the file. Make sure the directory exists.<a href=create.php>Back to main page</a>');		
	elseif($_GET['move'] != 'A-Z')
		@rename($rootdir.'/'.$_GET['dir'].'/'.$_GET['file'], $rootdir.'/'.$_GET['dir'].'/'.$_GET['move'].'/'.$_GET['file']) or error('Could not move the file. Make sure the directory exists.<a href=create.php>Back to main page</a>');
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
	echo '<form action="create.php">';
	if(str_replace('//', '', $dir) != '' && str_replace('//', '', $dir) != '/')
		echo '<a href="create.php?dir='.$up1dir.'"><img src="images/back.gif" alt="Up" width="25"/>Up 1 Directory</a> | <a href="create.php"><img src="images/home.gif" alt="Home" width="25"/>Home</a><br><br>';
	echo '<table width="78%" border="1" id="filelist">
<tr><th>#</th><th>Select</th><th width="36">Icon</th><th>Name</th><th>Function</th><th>Last Modified</th>';
	for($i = 0; $i< count($files); $i++)
	{
		$file = $files[$i];
		$type = substr($file, strrpos($file, '.') + 1, strlen($file));
		if(isset($types[$type]))
			$image = $types[$type];
		else
			$image = 'images';
		$size = filesize($rootdir.'/'.$dir.'/'.$file);
		$perms = substr(sprintf('%o', fileperms($rootdir.'/'.$dir.'/'.$file)), -4);
		$mtime = date("F d Y H:i:s", filemtime($rootdir.'/'.$dir.'/'.$file));
		$filenum = $i + 1;
		if(is_dir($rootdir.'/'.$dir.'/'.$file))
			echo '<tr><td>'.$filenum.'</td><td><input type="radio" name="file" value="'.$file.'"/></td width="36"><td><img src="images/folder.jpg" alt="Type"/></td><td><a href="create.php?cd='.$dir.'/'.$file.'">'.$file.'</a></td><td><input type="submit" class="delbutton" name="delete" value="Delete" onclick="return confirm("Are you sure yo want to delete?")" /></td><td>'.$mtime.'</td></tr>';
		else
			echo '<tr><td>'.$filenum.'</td><td><input type="radio" name="file" value="'.$file.'"/></td width="36"><td><img src="images/'.$image.'" alt="Type"/></td><td><a href="'.$rootdir.'/'.$dir.'/'.$file.'">'.$file.'</a></td><td><input type="submit" name="delete" value="Delete" class="delbutton"  onclick="return confirm("Are you sure yo want to delete?")" /></td><td>'.$mtime.'</td></tr>';
	}
?>
</table>
<br>
<table width="70%">

</table>
<table>
<form enctype="multipart/form-data" action="create.php" method="POST">
<tr><td>New Folder</td><td><input type="text" name="createdir" onKeyPress="return disableEnterKey(event)"/> <input type="submit" value="Create"/></td></tr>
<tr><td>Rename</td><td><input type="text" name="rename" onKeyPress="return disableEnterKey(event)"/> <input type="submit" name="submit" value="Rename"/></td></tr>
<tr><td>Move</td><td><input type="text" name="move" value="Name of new directory" onKeyPress="return disableEnterKey(event)"/> <input type="submit" name="submit" value="Move"/><br>
<tr><td>
<input type="hidden" name="MAX_FILE_SIZE" value="0" />
<input type="hidden" name="upload" value="1" />
<input type="hidden" name="dir" value="<?php echo $dir; ?>" />
Upload File</td><td><input name="uploadedfile" type="file" /> <input type="submit" class="tfbutton" value="Upload" />
</td>
</tr
</form>

</table>
<hr><br>
<?php
}
?>
        
<div id="footer">
Copyright © created by Nthabiseng Masipa
</div>

        
</body>
</html>
