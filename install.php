<?php
include("inc/conexion.php");
mysql_query("ALTER TABLE venta ADD cambio CHAR(1)  DEFAULT \"N\" NOT NULL AFTER st",$con1);
?>