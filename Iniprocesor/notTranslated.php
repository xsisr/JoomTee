<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<meta name="description" content="JoomTee based on Tabella and Nette">
	

	<title>JoomTee based on Tabella and Nette</title>

	<link rel="stylesheet" media="screen,projection,tv" href="../css/screen.css" type="text/css">
	<link rel="stylesheet" media="screen,projection,tv" href="../css/maite.tabella.css" type="text/css">
	<link rel="stylesheet" media="screen,projection,tv" href="../css/jquery.datepicker.css" type="text/css">
	<link rel="stylesheet" media="print" href="../css/print.css" type="text/css">
	<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    </head>
 
    <body>
<div id="wrapper">
	

	<h1>JoomTee</h1>

	<h2>
		<a href="../Iniprocesor/addLang.php">-Přidat jazyk</a>
		<a href="../Iniprocesor/importData.php">-Importovat</a>
		<a href="../Iniprocesor/exportData.php">-Exportovat</a>
		<a href="../Iniprocesor/notTranslated.php">-Nepřeložené</a>
	  <a href="../">-Editor</a>
   	
	</h2>

	<hr />

	

	   <?php
    include ("dbconnector.php");



    
    $nottranslated = mysql_query("SELECT DISTINCT
FILE , lang
FROM joomT
WHERE translation = ''");
    $r = mysql_num_rows($nottranslated);
    if ($r != 0) {
	echo "V následujících souborech jsou nepřeložené výrazy: <br/>";
	echo '<table style = "border: 1px dashed">';
	while ($ll = mysql_fetch_array($nottranslated)):
	    
	    $say = "<tr><td>" . $ll[1] . "</td><td>" . $ll[0] . "</td></tr>";
	    echo $say;
	endwhile;
	echo "</table>";
    }
    ?>

	<pre>
	  Systém používá <a href="http://www.nette.org">Nette Framework</a> a jeho add-on <a href="https://github.com/knyttl/Tabella">Tabella</a>
	</pre>
	
  </div>
    </body>
</html>

    </body>
</html>