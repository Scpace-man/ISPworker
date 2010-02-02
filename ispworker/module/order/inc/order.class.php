<?php

// order.class.php - Bestandteil des Frontends des ISPWorker Moduls "order"

class order
{
	function print_paketinfo($feld,$id)
	{
		global $db;

		$res = $db->query("SELECT $feld
		FROM order_artikel,biz_produkte
		WHERE order_artikel.artikelid=biz_produkte.produktid
		AND order_artikel.artikelid='$id'");
		$row = $db->fetch_array($res);

		echo stripslashes($row[$feld]);
	}
}

?>
