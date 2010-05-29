<?php
ini_set("max_execution_time","0");
ini_set("ignore_user_abort","1");
include("inc/conexion.php");
mysql_query("TRUNCATE TABLE prode2_pronostico");
$q_pronosticos = mysql_query("SELECT id_usuario, fecha, partido0, partido1, partido2, partido3, partido4, partido5, partido6, partido7, partido8, partido9 FROM prode_pronostico") or die(mysql_error());
while ($p = mysql_fetch_assoc($q_pronosticos)) {
	$id = 21 + (10 * ($p['fecha'] - 1));
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".$id.", '".$p['id_usuario']."', '".$p['partido0']."')") or die(mysql_error());
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(1 + $id).", '".$p['id_usuario']."', '".$p['partido1']."')");
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(2 + $id).", '".$p['id_usuario']."', '".$p['partido2']."')");
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(3 + $id).", '".$p['id_usuario']."', '".$p['partido3']."')");
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(4 + $id).", '".$p['id_usuario']."', '".$p['partido4']."')");
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(5 + $id).", '".$p['id_usuario']."', '".$p['partido5']."')");
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(6 + $id).", '".$p['id_usuario']."', '".$p['partido6']."')");
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(7 + $id).", '".$p['id_usuario']."', '".$p['partido7']."')");
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(8 + $id).", '".$p['id_usuario']."', '".$p['partido8']."')");
	mysql_query("INSERT INTO prode2_pronostico (partido, usuario, resultado) VALUES (".(9 + $id).", '".$p['id_usuario']."', '".$p['partido9']."')");
}
?>