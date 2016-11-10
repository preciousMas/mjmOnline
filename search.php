
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    #searchbox{
     position:relative;
     top:-40px;
     text-align: right;
 }
    
</style>
<script type="text/javascript">
   function getId(id) {
       return document.getElementById(id);
   }
   function validation() {
       getId("submit").style.display="none";
       getId("wait_tip").style.display="";
       return true;
   }
</script>
 <title>MJM Online</title>
  <link rel="stylesheet" type="text/css" href="search.css" />
         
        <link rel="stylesheet" type="text/css" href="style.css" />
          <link rel="stylesheet" type="text/css" href="css/list.css" />
          <link rel="css/stylesheet" type="text/css" href="style.css" />

        </head>
    
      
    <?php
    
    
    

$stored_config = 'AutoIndex.conf.php';

//now we need to include either the stored settings, or the config generator
if (@is_file($stored_config))
{
	if (!@include($stored_config))
	{
		die("<p>Error including file <em>$stored_config</em></p>");
	}
}

$this_file = (($index == '') ? $_SERVER['PHP_SELF'] : $index);
$this_file .= ((strpos($this_file, '?') !== false) ? '&' : '?');
$referrer = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'N/A');


$config_vars = array('base_dir', 'icon_path', 'stylesheet');
foreach ($config_vars as $this_var)
{
	if (!isset($$this_var))
	{
		die("<p>Error:.
		<br />The variable <strong>$this_var</strong> is not set.</p>
		<p>Delete <em>$stored_config</em> and then run <em>$config_generator</em>.</p>");
	}
}

//find the language the script should be displayed in
 if (!isset($_SESSION['lang']))
{
	$_SESSION['lang'] = $lang;
}
@include($path_to_language_files.$_SESSION['lang'].'.php');


function translate_uri($uri)
//rawurlencodes $uri, but not any slashes
{
	$uri = rawurlencode(str_replace('\\', '/', $uri));
	return str_replace(rawurlencode('/'), '/', $uri);
}

function get_basename($fn)
//returns everything after the slash, or the original string if there is no slash
{
	return basename(str_replace('\\', '/', $fn));
}

function match_in_array($string, &$array)
//returns true if $string matches anything in the array
{
	$string = get_basename($string);
	static $replace = array(
		'\*' => '[^\/]*',
		'\+' => '[^\/]+',
		'\?' => '[^\/]?');
	foreach ($array as $m)
	{
		if (preg_match('/^'.strtr(preg_quote(get_basename($m), '/'), $replace).'$/i', $string))
		{
			return true;
		}
	}
	return false;
}
      
function is_hidden($fn, $is_file = true)
//looks at $hidden_files and $show_only_these_files to see if $fn is hidden
{
	if ($fn == '')
	{
		return true;
	}
	
	global $hidden_files, $show_only_these_files;
	if ($is_file && count($show_only_these_files))
	{
		return (!match_in_array($fn, $show_only_these_files));
	}
	if (!count($hidden_files))
	{
		return false;
	}
	return match_in_array($fn, $hidden_files);
}

function eval_dir($d)
//check $d for "bad" things, and deal with ".."
{
	$d = str_replace('\\', '/', $d);
	if ($d == '' || $d == '/')
	{
		return '';
	}
	$dirs = explode('/', $d);
	for ($i=0; $i<count($dirs); $i++)
	{
		if ($dirs[$i] == '.' || is_hidden($dirs[$i], false))
		{
			array_splice($dirs, $i, 1);
			$i--;
		}
		else if (preg_match('/^\.\./', $dirs[$i])) //if it starts with two dots
		{
			array_splice($dirs, $i-1, 2);
			$i = -1;
		}
	}
	$new_dir = implode('/', $dirs);
	if ($new_dir == '' || $new_dir == '/')
	{
		return '';
	}
	if ($d{0} == '/' && $new_dir{0} != '/')
	{
		$new_dir = '/'.$new_dir;
	}
	if (preg_match('#/$#', $d) && !preg_match('#/$#', $new_dir))
	{
		$new_dir .= '/';
	}
	else if (is_hidden(get_basename($d)))
	{
		return '';
	}
	return $new_dir;
}

//get the user defined variables that are in the URL
$subdir = (isset($_GET['dir']) ? eval_dir(rawurldecode($_GET['dir'])) : '');
$file_dl = (isset($_GET['file']) ? rawurldecode($_GET['file']) : '');
$search = (isset($_GET['search']) ? $_GET['search'] : '');
$search_mode = (isset($_GET['searchMode']) ? $_GET['searchMode'] : '');
while (preg_match('#\\\\|/$#', $file_dl))
{
	$file_dl = substr($file_dl, 0, -1);
}
$file_dl = eval_dir($file_dl);

if (!@is_dir($base_dir))
{
	die('<p>Error: <em>'.htmlentities($base_dir)
	.'</em> is not a valid directory.<br />Check the $base_dir variable.</p>');
}

if (!$sub_folder_access || $subdir == '/')
{
	$subdir = '';
}
else if (preg_match('#[^/\\\\]$#', $subdir))
{
	$subdir .= '/'; //add a slash to the end if there isn't one
}

$dir = $base_dir.$subdir;

//this will be displayed before any HTML output
$html_heading = '';

if ($index == '')
{
	$html_heading .= '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>';
}
if ($stylesheet != '')
{
	$html_heading .= "\n<link rel=\"stylesheet\" href=\"$stylesheet\" type=\"text/css\" title=\"AutoIndex Default\" />\n";
}
if ($index == '')
{
	$html_heading .= "\n<title>".$words['index of'].' '.htmlentities($dir)
		."</title>\n\n</head><body class='autoindex_body'>\n\n";
}

function show_header()
{
	global $header, $header_per_folder, $dir;
	if ($header != '')
	{
		if ($header_per_folder)
		{
			$header = $dir.$header;
		}
		if (@is_readable($header))
		{
			include($header);
		}
	}
}

function ext($fn)
//return the lowercase file extension of $fn, not including the leading dot
{
	$fn = get_basename($fn);
	return (strpos($fn, '.') ? strtolower(substr(strrchr($fn, '.'), 1)) : '');
}

function get_all_files($path)
//returns an array of every file in $path, including folders (except ./ and ../)
{
	$list = array();
	if (($hndl = @opendir($path)) === false)
	{
		return $list;
	}
	while (($file=readdir($hndl)) !== false)
	{
		if ($file != '.' && $file != '..')
		{
			$list[] = $file;
		}
	}
	closedir($hndl);
	return $list;
}

function get_file_list($path)
//returns a sorted array of filenames. Filters out "bad" files
{
	global $sub_folder_access, $links_file;
	$f = $d = array();
	foreach (get_all_files($path) as $name)
	{
		if ($sub_folder_access && @is_dir($path.$name) && !is_hidden($name, false))
		{
			$d[] = $name;
		}
		else if (@is_file($path.$name) && !is_hidden($name, true))
		{
			$f[] = $name;
		}
	}
	if ($links_file != '' && ($links = @file($path.$links_file)))
	{
		foreach ($links as $name)
		{
			$p = strpos($name, '|');
			$f[] = (($p === false) ? rtrim($name).'|' : substr(rtrim($name), 0, $p).'|');
		}
	}
	natcasesort($d);
	natcasesort($f);
	return array_merge($d, $f);
}
function match_filename($filename, $string)
{
	if (preg_match_all('/(?<=")[^"]+(?=")|[^ "]+/', $string, $matches))
	{
		foreach ($matches[0] as $w)
		{
			if (preg_match('#[^/\.]+#', $w) && stristr($filename, $w))
			{
				return true;
			}
		}
	}
	return false;
}

function search_dir($sdir, $string)
//returns files/folders (recursive) in $sdir that contain $string
{
	global $search_mode;
	//search_mode: d=folders, f=files, fd=both

	$found = array();
	$list = get_file_list($sdir);
	$d = count($list);
	for ($i=0; $i<$d; $i++)
	{
		$full_name = $sdir.$list[$i];
		if (stristr($search_mode, 'f') && (@is_file($full_name) || preg_match('/\|$/', $list[$i])) && match_filename($list[$i], $string))
		{
			$found[] = $full_name;
		}
		else if (@is_dir($full_name))
		{
			if (stristr($search_mode, 'd') && match_filename($list[$i], $string))
			{
				$found[] = $full_name;
			}
			$found = array_merge($found, search_dir($full_name.'/', $string));
		}
	}
	return $found;
}

function add_num_to_array($num, &$array)
{
	isset($array[$num]) ? $array[$num]++ : $array[$num] = 1;
}
function redirect($site)
{
	header("Location: $site");
	die('<p>Redirection header could not be sent.<br />'
		."Continue here: <a href=\"$site\">$site</a></p>");
}

function icon($ext)
//find the appropriate icon depending on the extension (returns a link to the image file)
{
	global $icon_path;
	if ($icon_path == '')
	{
		return '';
	}
	if ($ext == '')
	{
		$icon = 'generic';
	}
	else
	{
		$icon = 'unknown';
		static $icon_types = array(
		'comp' => array('cfg', 'conf', 'inf', 'ini', 'log', 'nfo', 'reg'),
		'compressed' => array('7z', 'a', 'ace', 'ain', 'alz', 'amg', 'arc',
			'rar,tgz', 'tz', 'tzb', 'uc2', 'xxe', 'yz', 'z', 'zip', 'zoo'),
		
		'doc' => array('abw', 'ans', 'chm', 'cwk', 'dif', 'doc', 'dot',
			'mcw', 'msw', 'pdb', 'psw', 'rtf', 'rtx', 'sdw', 'stw', 'sxw',
			'vor', 'wk4', 'wkb', 'wpd', 'wps', 'wpw', 'wri', 'wsd'),
		'image' => array('adc', 'art', 'bmp', 'cgm', 'dib', 'gif', 'ico',
			'ief', 'jfif', 'jif', 'jp2', 'jpc', 'jpe', 'jpeg', 'jpg', 'jpx'),
		'pdf' => array('edn', 'fdf', 'pdf', 'pdp', 'pdx'),
		'text' => array('c', 'cc', 'cp', 'cpp', 'cxx', 'diff', 'h', 'hpp',
			'hxx', 'm3u', 'md5', 'patch', 'pls', 'py', 'sfv', 'sh',
			'txt'),
		'xls' => array('csv', 'dbf', 'prn', 'pxl', 'sdc', 'slk', 'stc', 'sxc',
			'xla', 'xlb', 'xlc', 'xld', 'xlr', 'xls', 'xlt', 'xlw'));
		foreach ($icon_types as $png_name => $exts)
		{
			if (in_array($ext, $exts))
			{
				$icon = $png_name;
				break;
			}
		}
	}
	return "<img alt=\"[$ext]\" height=\"16\" width=\"16\" src=\"$icon_path/$icon.png\" /> ";
}
if ($file_dl != '')
//if the user specified a file to download, download it now
{
	if (!@is_file($dir.$file_dl))
	{
		header('HTTP/1.0 404 Not Found');
		echo $html_heading;
		show_header();
		echo '<h3>Error 404: file not found</h3>',
			htmlentities($dir . $file_dl), ' was not found on this server.</br><a href=create.php>Back to main page</a>';
	
		die();
	}
	redirect(translate_uri($dir.$file_dl));
}
echo $html_heading;
show_header();

if (!@is_dir($dir))
//make sure the subfolder exists
{
	echo '<p><strong>Error: The folder <em>'.htmlentities($dir)
		.'</em> does not exist.</strong></p>';
	$dir = $base_dir;
	$subdir = '';
}

if ($enable_searching && $search != '')
//show the results of a search
{
	echo '<table border="0" cellpadding="8" cellspacing="0">
		<tr class="paragraph"><td class="default_td"><p><strong>',
		$words['search results'], '</strong> :<br /><span class="small">for <em>',
		htmlentities($dir), '</em> and its subdirectories</span></p><p>';
	$results = search_dir($dir, $search);
	natcasesort($results);
	for ($i=0; $i<count($results); $i++)
	{
		$file = substr($results[$i], strlen($base_dir));
		echo '<a class="default_a" href="'.$this_file;
		if (is_dir($base_dir.$file))
		{
			echo 'dir='.translate_uri($file).'/">';
			if ($icon_path != '')
			{
				echo '<img height="16" width="16" alt="[dir]" src="', $icon_path, '/dir.png" /> ';
			}
			echo htmlentities($file)."/</a><br />\n";
		}
		else if (preg_match('/\|$/', $file))
		{
			$file = substr($file, 0, -1);
			$display = get_stored_info($file, $dir.$links_file);
			if ($display == '')
			{
				$display = $file;
			}
			echo 'dir=', translate_uri($subdir), '&amp;link=',
			translate_uri($file), '" title="Link to: ', $file, '">',
			icon(ext($display)), htmlentities($display), '</a><br />';
		}
		else
		{
			echo 'dir=', translate_uri(dirname($file)).'/&amp;file=',
			translate_uri(get_basename($file)), '">',
			icon(ext($file)), htmlentities($file), "</a><br />\n";
		}
	}
}
if ($enable_searching)
{
	@show_search_box();
}

	echo '</body></html>';


                     