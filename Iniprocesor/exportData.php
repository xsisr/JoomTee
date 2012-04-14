<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<meta name="description" content="Tabella addon example site">
	<meta name="robots" content="{$robots}" n:ifset="$robots">

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
	$display = true;
	$languages = mysql_query("SELECT DISTINCT lang from joomT");
	
	if (!empty($_POST)) {
	    if (strlen($_POST["lang"]) <> 5) {

		echo "Zadejte platný pětimístný formát jazykové zkratky";
	    } else {
		$display = false;
		$nahraj = mysql_query("SELECT DISTINCT file FROM joomT");
echo "Generované soubory<br>";
		while ($i = mysql_fetch_array($nahraj)) :
		    $path = "./generatedFiles/" . $_POST["lang"] . "." . $i["file"] . ".ini";
		    $fp = fopen($path, "w");
		    $v = mysql_query("select * from joomT where lang=" . '("' . $_POST["lang"] . '")' . "AND file = " . '("' . $i["file"] . '")');
		    $r = mysql_num_rows($v);
		    if ($r == 0)
			echo "Zadaný jazykový kod " . $_POST["lang"] . " neexistuje";
		    else {
			echo "Generuji v jazyce " . $_POST["lang"] . " konfigurační soubor".'"(<b>' . $i["file"] . '</b>)"'." obsahující $r záznamů.<BR />";
			
			

			while ($zaznamy = MySQL_Fetch_Array($v)):

			    $file = $zaznamy["key"] . " = " . '"' . $zaznamy["translation"] . '"' . "\n";
			    fwrite($fp, $file);
			endwhile;
		    }
		    fclose($fp);
		    chmod($path, 0777);
		endwhile;
	    }
	};
	?>

	

	<? if ($display): ?>
    	<form method="post" action="<? echo $_SERVER["PHP_SELF"] ?>">
    	    Language (vyplňte dle vzorového formátu) <input name="lang" value="en-GB">
    	    <input type="Submit" name="odesli"><br/>
	    Jazyky v databázi k dispozici: <ul><?php
		while ($ll=MySQL_Fetch_Array($languages)):
		    
		    $say= "<li>".$ll['lang']."</li>";
		    echo $say;
			endwhile;
		?>
	    </ul>
    	</form>
	<? endif; ?>
    <a href="../">Zpět na hlavní stránku</a>

	<pre>
	  Systém používá <a href="http://www.nette.org">Nette Framework</a> a jeho add-on <a href="https://github.com/knyttl/Tabella">Tabella</a>
	</pre>
	
  </div>
    </body>
</html>
