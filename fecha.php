<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }
if (count($_POST) > 0) {
	if (count($_POST['partido']) > 0) {
		foreach ($_POST['partido'] as $p => $a) { $partido[] = $p; }
		if (count($partido) == 1) { $partidos = "'".$partido[0]."'"; } else { $partidos = "'".implode("','",$partido)."'"; }
		$q_partidos = mysql_query("SELECT id_partido, if(pr.resultado IS NOT NULL,'N','S') AS nuevo FROM prode_partido pa LEFT JOIN prode2_pronostico pr ON pa.id_partido = pr.partido AND pr.usuario = '".$_SESSION['id']."' WHERE dia > NOW() AND id_partido IN (".$partidos.")");
		unset($partidos);
		while ($partido = mysql_fetch_assoc($q_partidos)) {
			if ($partido['nuevo'] == "N") {
				mysql_query("UPDATE prode2_pronostico SET resultado = '".$_POST['partido'][$partido['id_partido']]."' WHERE partido = '".$partido['id_partido']."' AND usuario = '".$_SESSION['id']."'");
			} else {
				mysql_query("INSERT INTO prode2_pronostico (id_pronostico, partido, usuario, resultado) VALUES (NULL, '".$partido['id_partido']."', '".$_SESSION['id']."', '".$_POST['partido'][$partido['id_partido']]."')");
			}
		}
	}
	header("location:ok.php?ok=prode&fecha=".$_GET['fecha']);
	exit;
}

if (!empty($_GET['fecha'])) {
	$q_jugado = mysql_query("SELECT COUNT(id_partido) as jugados FROM prode_partido WHERE fecha = '".$_GET['fecha']."' AND NOW() >= dia");
	$jugado = mysql_fetch_assoc($q_jugado);
	$q_partidos = mysql_query("SELECT if(dia < NOW(),'S','N') AS disabled, e1.corto AS local, e1.id_equipo AS e1, e2.corto AS visitante, e2.id_equipo AS e2, id_partido, date_format(dia,'%d/%m/%y %H:%i') AS diashow, pr.resultado FROM prode_partido p LEFT JOIN prode_equipo e1 ON p.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON p.visitante = e2.id_equipo LEFT JOIN prode2_pronostico pr ON p.id_partido = pr.partido AND usuario = '".$_SESSION['id']."' WHERE fecha = '".$_GET['fecha']."'");
	if ($jugado['jugados'] == mysql_num_rows($q_partidos)) { header("location:tablaprode.php?jugador=".$_SESSION['id']."&fecha=".$_GET['fecha']); exit; }
} else {
	$q_partidos = mysql_query("SELECT if(dia < NOW(),'S','N') AS disabled, e1.corto AS local, e1.id_equipo AS e1, e2.corto AS visitante, e2.id_equipo AS e2, id_partido, date_format(dia,'%d/%m/%y %H:%i') AS diashow, pr.resultado FROM prode_partido p LEFT JOIN prode_equipo e1 ON p.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON p.visitante = e2.id_equipo LEFT JOIN prode2_pronostico pr ON p.id_partido = pr.partido AND usuario = '".$_SESSION['id']."' WHERE TO_DAYS(dia) >= TO_DAYS(NOW()) AND TO_DAYS(dia) < to_days(now()) + 8");
}
$q_mensajes = mysql_query("SELECT id_mensaje, fecha, usuario, id_usuario, mensaje, DATE_FORMAT(dia,'%d/%m/%y %H:%i') AS diashow FROM prode_mensaje m LEFT JOIN prode_usuario u ON m.autor = u.id_usuario ORDER BY fecha DESC, dia DESC LIMIT 0,3");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Partidos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<META HTTP-EQUIV="expires" CONTENT="Wed, 26 Feb 1997 08:21:57 GMT" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<META HTTP-EQUIV="Content-Script-Type" CONTENT="text/javascript" />
	<META HTTP-EQUIV="Content-Style-Type" CONTENT="text/css" />
	<META http-equiv="Default-Style" content="compact" />
	<META HTTP-EQUIV="Content-Language" CONTENT="es-AR" />
	<META HTTP-EQUIV="cache-directive" CONTENT=" cache-response-directive" />
	<META HTTP-EQUIV="cache-request-directive" CONTENT="max-age=60" />
	<META HTTP-EQUIV="cache-response-directive" CONTENT="public" />
	<META HTTP-EQUIV="Vary" CONTENT="Content-language" />
	<META NAME="ROBOTS" CONTENT="INDEX,FOLLOW" />
	<META NAME="description" CONTENT="Prode para el torneo de f&uacute;tbol argentino de Primera Divisi&oacute;n" />
	<META NAME="keywords" CONTENT="prode, pronóstico deportivo, fútbol, torneo, apertura, clausura, argentina " />
	<META NAME="author" CONTENT="Seppo" />
	<META NAME="rating" CONTENT="safe for kids" />
	<link rel="Principal" title="Página principal" href="index.php" />
	<link rel="stylesheet" href="css/estilo.css" type="text/css" />
</head>
<body>
<?php include("inc/menu.php"); ?>
<div id="ppal">
<?php include("inc/fechas.php"); ?>
<h1>Pr&oacute;ximos partidos</h1>
<?php if (mysql_num_rows($q_partidos) == 0) { ?>
<h2>No hay partidos no jugados cargados</h2>
<?php } else { ?>
<form action="fecha.php?fecha=<?php echo$_GET['fecha'];?>" method="post">
	<table>
	<?php while ($partido = mysql_fetch_assoc($q_partidos)) { ?>
		<tr>
			<td align="right"><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="L" id="L<?php echo$partido['id_partido'];?>" <?php if ($partido['resultado'] == "L") { echo "checked "; } if ($partido['disabled'] == "S") { echo "disabled "; } ?>/></td>
			<td><?php if (file_exists("escudos/equipo".$partido['e1'].".gif")) { ?><label for="L<?php echo$partido['id_partido'];?>" title="<?php echo$partido['local']; ?>"><img src="escudos/equipo<?php echo $partido['e1']; ?>.gif" alt="<?php echo$partido['local']; ?>" height="30" /></label><?php } ?></td>
			<td align="right"><label for="L<?php echo$partido['id_partido'];?>" title="<?php echo$partido['local']; ?>"><?php echo $partido['local']; ?></label></td>
			<td align="center"><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="E" <?php if ($partido['resultado'] == "E") { echo "checked "; } if ($partido['disabled'] == "S") { echo "disabled "; } ?>/></td>
			<td><label for="V<?php echo$partido['id_partido'];?>" title="<?php echo$partido['visitante']; ?>"><?php echo $partido['visitante']; ?></label></td>
			<td><?php if (file_exists("escudos/equipo".$partido['e2'].".gif")) { ?><label for="V<?php echo$partido['id_partido'];?>" title="<?php echo$partido['visitante']; ?>"><img src="escudos/equipo<?php echo $partido['e2']; ?>.gif" alt="<?php echo$partido['visitante']; ?>" height="30" /></label><?php } ?></td>
			<td><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="V" id="V<?php echo$partido['id_partido'];?>" <?php if ($partido['resultado'] == "V") { echo "checked "; } if ($partido['disabled'] == "S") { echo "disabled "; } ?>/></td>
			<td><?php echo $partido['diashow']; ?></td>
		</tr>
	<?php } ?>
		<tr>
			<td></td>
			<td colspan="7"><input type="submit" name="enviar" value="Guardar prode" class="button" /></td>
		</tr>
	</table>
</form>
<?php } ?>
<p>&nbsp;</p>
<h1>&Uacute;ltimos mensajes</h1>
<table cellspacing="0" width="100%">
	<?php while ($msg = mysql_fetch_assoc($q_mensajes)) { ?>
	<tr style="font-size:medium;">
		<td>De: <a href="tablaprode.php?jugador=<?php echo $msg['id_usuario'];; ?>"><?php echo $msg['usuario']; ?></a><?php if (!empty($_SESSION['admin'])) { ?> <a href="mensaje.php?accion=eliminar&amp;mensaje=<?php echo $msg['id_mensaje']; ?>" style="font-size:xx-small">Eliminar mensaje</a><?php } ?></td>
		<td align="right"><?php echo $msg['diashow']; ?></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo autolink(nl2br($msg['mensaje'])); ?></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2"><a href="mensaje.php">Ver m&aacute;s mensajes</a></td>
	</tr>
</table>
</div>
</body>
</html>
