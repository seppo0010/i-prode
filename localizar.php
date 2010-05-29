<?php
include("inc/conexion.php");
for ($p=1;$p<=190;$p++) {
	$check = mysql_query("SELECT id_pronostico FROM prode2_pronostico WHERE partido = '".$p."' AND usuario = '".$_GET['usuario']."'");
	if (mysql_num_rows($check) == 0) {
		mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES ('".$p."', '".$_GET['usuario']."', 'L')");
		echo "Agregado partido ".(($p - 1) % 10 + 1)." fecha ".(floor(($p - 1) / 10) + 1 )."<br />";
	} else {
		echo "NO Agregado partido ".(($p - 1) % 10 + 1)." fecha ".(floor(($p - 1) / 10) + 1 )." (ya existente)<br />";	}
}
?>