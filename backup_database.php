<?php
if(isset($_POST['zipMeNow'])) {
    

    ini_set('max_execution_time', 3600); // increase PHP execution time for large files

    // get path for the folder // I have zipped the /themes/ folder as an example
    $rootPath = 'backup/';
   
    $fileName=date("Y.m.j-h.i.s");

    $zip = new ZipArchive();
    $zip->open('Backup.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE); // change filename or make it dynamic

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);

    foreach($files as $name => $file) {
        if(!$file->isDir()) { // skip directories (they will be added automatically)
            // get real and relative path for current file
            $filePath = $file->getRealPath();
            //$relativePath = substr($filePath, strlen($rootPath) + 1); // use this if you get a corrupt archive
            $relativePath = substr($filePath, strlen($rootPath));

            // add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();

    // send headers to force download the file // or send it to Dropbox or Google Drive
    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename=Backup.$fileName.zip");
    //header("Content-length: " . filesize('file.zip')); // not necessary
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile("Backup.zip");
    exit;
}
?>
<?php

$db_base="online";
$db=mysql_connect("localhost","root","")
or die ("unable to connect");
$select_base=mysql_selectdb($db_base,$db);


function get_para_value($name, $default_value) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS, $_GET, $_POST;
    if( isset($_GET[$name])) {
        return $_GET[$name];
    }
    if( isset($_POST[$name])) {
        return $_POST[$name];
    }
    return $default_value;
}


$action=get_para_value('action', 'ask');

if ($action=='ask') {
    ?>
    <html>
    <body>
    <head>
      <link rel="stylesheet" type="text/css" href="search.css" />  
        
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
#secti
#footer {
    background-color:#50a6c2;
    color:white;
    clear:both;
    text-align:center;
   padding:5px;
   height:70px;
}
</style>
    <SCRIPT LANGUAGE="JavaScript">
    var checkflag = "false";
    function check(form) {
        var objCheckBoxes = form.elements;
        if (checkflag == "false") {
            for (i = 0; i < objCheckBoxes.length; i++) {
                objCheckBoxes[i].checked = true;
            }
            checkflag = "true";
            return "Uncheck all";
        } else {
            for (i = 0; i < objCheckBoxes.length; i++) {
                objCheckBoxes[i].checked = false;
            }
            checkflag = "false";
            return "Check all";
        }
    }
    </script>
    </head>
    <center>
        
    
<div id="header">
 
    MJM Online Admin Panel
    </div>
        
<div id="nav">
 <input type="button"  name="submit" class='tfbutton' onclick="location.href='index.php'" value="Logout"/>
       
  <input type="button"  name="submit" class='tfbutton' onclick="location.href='.database.php'" value="Next"/><br/>
         <br/><br/>       
      <br/>     
 <input type="button"  name="submit" class='navbutton' onclick="location.href='backup_database.php'" value="system backups"/><br/>
 <input type="button"  name="submit" class='navbutton' onclick="location.href='database.php'" value="Manage Users"/><br/>
 <input type="button"  name="submit" class='navbutton' onclick="location.href='directory.php'" value="Manage Directory"/><br/>
 
</div>         
        

    <form name=dump_form action=backup_database.php method="post" enctype="multipart/form-data" accept-charset="utf-8">
    <br>
    Choose tables to delete :
    <br>
    <br>
    <input type=button value="Check all" onClick="this.value=check(this.form)">
    <br>
    <br>
    <table>
        </div>  
    <?php
    
    $sql_tables = "SHOW TABLES";
    $req_tables = mysql_query($sql_tables);
    while (list($table) = mysql_fetch_row($req_tables)) {
      
        echo "<tr><td><input type=checkbox name=".$table."> ".$table."</td></tr>";
        
    }
    ?>
    </fieldset>     
    </table>
    <input type=hidden name=action value=dump>
    <br>
    <input type=button class='navbutton' value="Download Database Backup" onClick="this.form.action.value='dump';this.form.submit();">
    <input type="submit" class='navbutton' name="zipMeNow" value=" Dowmload Files Backup">
    </form>
    </center>
    </body>
    </html>
    <?php
} else if ($action=='dump') {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment;filename=\"$db_base.sql\"");
    header("Content-Transfer-Encoding: binary");
    echo "--\n";
    echo "-- Dump of database $db_base\n";
    echo "--\n";
    $sql_tables = "SHOW TABLES";
    $req_tables = mysql_query($sql_tables);
    while (list($table) = mysql_fetch_row($req_tables)) {
        if (get_para_value($table,'no')=='no') continue;
        echo "\n--\n-- Table $table\n\n";
        echo "DROP TABLE IF EXISTS $table;\n";
        $sql_create_table = "SHOW CREATE TABLE $table";
        $req_create_table = mysql_query($sql_create_table);
        $create_table = mysql_fetch_array($req_create_table);
        echo $create_table[1].";\n";
        echo "\n--\n-- Filling de $table\n\n";
        $sql_fill_table = "SELECT * FROM $table";
        $req_fill_table = mysql_query($sql_fill_table);
        while ($row = mysql_fetch_assoc($req_fill_table)) {
        $line_insert = "INSERT INTO $table (";
        $l_value = ") VALUES (";
        foreach ($row as $field => $value) {
            $line_insert .= "`$field`, ";
            $l_value .= "'".mysql_real_escape_string($value)."', ";
        }
        $line_insert = substr($line_insert, 0, -2);
        $l_value = substr($l_value, 0, -2);
        echo $line_insert.$l_value.");\n";
       }
    }

    echo "--\n";
    echo "-- Dump of base $db_base finished )\n";
    echo "--\n";
} else if ($action=='dump_to_disk') {
    $file = fopen($db_base.".sql", 'w') or die('bug!');
    fwrite($file, "--\n");
    fwrite($file,  "-- Dump of the base $db_base\n");
    fwrite($file,  "--\n");
    $sql_tables = "SHOW TABLES";
    $req_tables = mysql_query($sql_tables);
    while (list($table) = mysql_fetch_row($req_tables)) {
        if (get_para_value($table,'no')=='no') continue;
        fwrite($file,  "\n--\n-- Table $table\n\n");
        fwrite($file,  "DROP TABLE IF EXISTS $table;\n");
        $sql_create_table = "SHOW CREATE TABLE $table";
        $req_create_table = mysql_query($sql_create_table);
        $create_table = mysql_fetch_array($req_create_table);
        fwrite($file,  $create_table[1].";\n");
        fwrite($file,  "\n--\n-- Remplissage de $table\n\n");
        $sql_fill_table = "SELECT * FROM $table";
        $req_fill_table = mysql_query($sql_fill_table);
        while ($row = mysql_fetch_assoc($req_fill_table)) {
        $line_insert = "INSERT INTO $table (";
        $l_value = ") VALUES (";
        foreach ($row as $field => $value) {
            $line_insert .= "`$field`, ";
            $l_value .= "'".mysql_real_escape_string($value)."', ";
        }
        $line_insert = substr($line_insert, 0, -2);
        $l_value = substr($l_value, 0, -2);
        fwrite($file,  $line_insert.$l_value.");\n");
       }
    }
    fwrite($file, "--\n");
    fwrite($file, "-- Dump of base $db_base finished (not even a crash!)\n");
    fwrite($file, "--\n");
    fclose($file);
    echo "dump done";
}
?>
    <div id="footer">
Copyright © created by Nthabiseng Masipa
</div>
</body>
</html>