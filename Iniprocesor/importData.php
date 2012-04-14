<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<meta name="description" content="JoomTee based on Tabella and Nette">
	

	<title>JoomTee based on Tabella and Nette</title>

	<link rel="stylesheet" media="screen,projection,tv" href="../css/screen.css" type="text/css">
	<link rel="stylesheet" media="screen,projection,tv" href="../css/maite.tabella.css" type="text/css">
	<link rel="stylesheet" media="screen,projection,tv" href="../css/jquery.datepicker.css" type="text/css">
	<link rel="stylesheet" media="print" href="../css/print.css" type="text/css">
	<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    </head>
   
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

//načte soubory ze složky ./files a rozřeže je pomocí ini parseru
    function getRecord($folder) {
	$zaznamy = array();
	$filedir = dir($folder);
	while ($soubor = $filedir->read()) {
	    if ($soubor == "." || $soubor == ".." && $soubor != "*.ini")
		continue;
	    $langfilename = explode(".", $soubor);
	    $lang = $langfilename[0];
	    $filename = $langfilename[1];
	    $inifile = "./files/$soubor";
	    $ini_array = parse_ini_file($inifile);
	    foreach ($ini_array as $key => $value) {

		$zaznam = array($filename, $key, $lang, $value);

		array_push($zaznamy, $zaznam);
	    }
	}
	$filedir->close();
	return $zaznamy;
    }

    $xx = getRecord("./files");
    saveRecord($xx);

    function saveRecord($zaznamy) {
	$insert1 = 'INSERT INTO joomT VALUES ';
	foreach ($zaznamy as $zaznam) {
	    $insert1 .= '("","' . $zaznam[0] . '","' . $zaznam[1] . '","' . $zaznam[2] . '","' . $zaznam[3] . '"),';
	}
	$insert1 = substr($insert1, 0, -1);
	$insert1 .= ";";

	//echo $insert1, "<br />",$insert2, "<br />", $insert3, "<br />", $insert4, "<br />";


	$insertAll = "$insert1";
//	echo $insertAll;
	echo "<br />";
	mysql_query($insertAll) . mysql_affected_rows() or die("Data nemohla být v pořádku nahrána do databáze kvůli následující chybě: <br />" . mysql_error());
	if (mysql_affected_rows() > 0) {
	    echo "Nová data úspěšně nahrána<br><br>";
	} else {
	    echo "Nic nebylo změňeno, data se shodují s již existujícími v databázi.<br><br>";
	}
    }
    ?>
    

	<pre>
	  Systém používá <a href="http://www.nette.org">Nette Framework</a> a jeho add-on <a href="https://github.com/knyttl/Tabella">Tabella</a>
	</pre>
	
  </div>

    <body>


    </body>
</html>