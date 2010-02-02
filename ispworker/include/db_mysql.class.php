<?php

class db_mysql extends db_accountdata
{
    function db_mysql()
    {
	$this->connect($this->host,$this->user,$this->pass,$this->datenbank);
    }

    function connect($host,$user,$pass,$datenbank)
    {
	$this->link = mysql_connect($host,$user,$pass) or die ("Datenbankverbindung nicht möglich!");
        $this->choosedb($datenbank);
    }

    function choosedb($datenbank)
    {
	@mysql_select_db($datenbank) or die ("Datenbank $datenbank konnte nicht ausgewählt werden!");
    }

    function query($query)
    {
	$res = mysql_query($query, $this->link) or die ("SQL Abfrage ist ungültig. ".mysql_error());
	return $res;
    }

    function queryfetch($query)
    {
	$res = mysql_query($query, $this->link) or die ("SQL Abfrage ist ungültig. ".mysql_error());
	return mysql_fetch_array($res);
    }

    function fetch_array($res)
    {
	return mysql_fetch_array($res);
    }

    function fetch_row($res)
    {
	return mysql_fetch_row($res);
    }

    function num_fields($res)
    {
	return mysql_num_fields($res); 
    }

    function fetch_field($res,$i)
    {
	return mysql_fetch_field($res,$i);
    }

    function num_rows($res)
    {
	return mysql_num_rows($res);
    }
   
    function insert_id()
    {
	return mysql_insert_id();
    }
}

// Ausnahmsweise Instanzierung gleich hier
$db = new db_mysql;

?>
