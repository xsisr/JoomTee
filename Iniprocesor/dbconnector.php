<?php
include("config.php");
mysql_connect(SQL_HOST,SQL_USERNAME, SQL_PASSWORD) or die("Nelze se připojit k serveru". mysql_error()); ;
mysql_select_db(SQL_DBNAME ) or die("Nelze se připojit k serveru". mysql_error());
?>
