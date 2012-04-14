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
	$display = true;

	if (!empty($_POST)) {
	    if (strlen($_POST["lang"]) <> 5) {

		echo "Zadejte platný pětimístný formát jazykové zkratky";
	    } else {
		$display = false;

		$lan = mysql_query("SELECT DISTINCT lang from joomT where lang=" . '("' . $_POST["lang"] . '")');
		$rr = mysql_numrows($lan);
		if ( $rr == 0) {


		    $nahraj = mysql_query("SELECT DISTINCT file FROM joomT where lang = 'en-GB'");
		    echo "Generovaná data<br>";
		    while ($i = mysql_fetch_array($nahraj)) :


			$v = mysql_query("select * from joomT where lang=" . '("' . $_POST["lang"] . '")' . "AND file = " . '("' . $i["file"] . '")');
			$r = mysql_num_rows($v);
			$x = mysql_query("select * from joomT where lang= 'en-GB' AND file = " . '("' . $i["file"] . '")');

			echo "Vytvářím " . $_POST["lang"] . " nové záznamy pro soubor" . '"(<b>' . $i["file"] . '</b>)"' . "V jazyce " . $_POST["lang"] . "<br/>";



			while ($zaznamy = MySQL_Fetch_Array($x)):

			    $insert = 'INSERT INTO joomT VALUES ';

			    $insert .= '("","' . $i["file"] . '","' . $zaznamy['key'] . '","' . $_POST["lang"] . '",""),';

			    $insert = substr($insert, 0, -1);
			    $insert .= ";";

			    mysql_query($insert) . mysql_affected_rows() or die("Data nemohla být v pořádku nahrána do databáze kvůli následující chybě: <br />" . mysql_error());


			endwhile;



		    endwhile;
		}else {
		    echo "Zadaný jazykový kód již v databázi existuje";
		}
	    }
	};
	?>
	

	<? if ($display): ?>
    	<form method="post" action="<? echo $_SERVER["PHP_SELF"] ?>">
    	    Vytvořte záznamy pro nový jazyk <br/>(vyplňte zkratku jazyka dle vzorového formátu) <input name="lang" value="en-GB">
    	    <input type="Submit" name="Vytvořit"><br/> Po odeslání může proces nějakou chvíli trvat, nevypínejte toto okno.
    	</form>
	<? endif; ?>
   

	<pre>
	  Systém používá <a href="http://www.nette.org">Nette Framework</a> a jeho add-on <a href="https://github.com/knyttl/Tabella">Tabella</a>
	</pre>
	
  </div>
    </body>
</html>
