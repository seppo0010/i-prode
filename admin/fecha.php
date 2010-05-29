<?php
require_once("../inc/conexion.php");
if (empty($_SESSION['admin'])) { header("location:../error.php?error=nousuario"); exit; }

if (count($_POST) > 0) {
	foreach ($_POST['dia'] as $id => $dia) {
		$dia = "20".substr($dia,6,2)."-".substr($dia,3,2)."-".substr($dia,0,2);
		mysql_query("UPDATE prode_partido SET dia = '".$dia."', resultado = '".$_POST['partido'][$id]."' WHERE id_partido = '".$id."'");
	}
	header("location:fecha.php?fecha=".$_GET['fecha']);
	exit;
}

if (!empty($_GET['fecha'])) {
	$q_partidos = mysql_query("SELECT id_partido, resultado, IF(NOW() < dia,'S','N') AS disabled, e1.corto AS local, e1.id_equipo AS e1, e2.corto AS visitante, e2.id_equipo AS e2, DATE_FORMAT(dia,'%d/%m/%y') AS diashow FROM prode_partido pa LEFT JOIN prode_equipo e1 ON pa.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON pa.visitante = e2.id_equipo WHERE fecha = '".$_GET['fecha']."' ORDER BY dia DESC") or die(mysql_error());
} else {
	$q_partidos = mysql_query("SELECT if(dia > NOW(),'S','N') AS disabled, e1.corto AS local, e1.id_equipo AS e1, e2.corto AS visitante, e2.id_equipo AS e2, id_partido, date_format(dia,'%d/%m/%y') AS diashow, p.resultado FROM prode_partido p LEFT JOIN prode_equipo e1 ON p.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON p.visitante = e2.id_equipo WHERE TO_DAYS(dia) <= TO_DAYS(NOW()) ORDER BY dia DESC LIMIT 0,20") or die(mysql_error());
}
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
	<link rel="Principal" title="Página principal" href="../index.php" />
	<link rel="stylesheet" href="../css/estilo.css" type="text/css" />
</head>
<body>
<?php include("inc/menu.php"); ?>
<div id="ppal">
<?php include("../inc/fechas.php"); ?>
<h1>&Uacute;ltimos partidos</h1>
<?php if (mysql_num_rows($q_partidos) == 0) { ?>
<h2>No hay partidos no jugados cargados</h2>
<?php } else { ?>
<form action="fecha.php?fecha=<?php echo$_GET['fecha'];?>" method="post">
	<table>
	<?php while ($partido = mysql_fetch_assoc($q_partidos)) { ?>
		<tr>
			<td><input type="text" name="dia[<?php echo $partido['id_partido'];?>]" value="<?php echo $partido['diashow']; ?>" /></td>
			<td align="right"><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="L" id="L<?php echo $partido['id_partido'];?>" <?php if ($partido['resultado'] == "L") { echo "checked "; } if ($partido['disabled'] == "S") { echo "disabled "; } ?>/></td>
			<td><label for="L<?php echo$partido['id_partido'];?>" title="<?php echo$partido['local']; ?>"><img src="../escudos/equipo<?php echo $partido['e1']; ?>.gif" alt="<?php echo$partido['local']; ?>" height="30" /></label></td>
			<td align="right"><label for="L<?php echo$partido['id_partido'];?>" title="<?php echo$partido['local']; ?>"><?php echo $partido['local']; ?></label></td>
			<td align="center"><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="E" <?php if ($partido['resultado'] == "E") { echo "checked "; } if ($partido['disabled'] == "S") { echo "disabled "; } ?>/></td>
			<td><label for="V<?php echo$partido['id_partido'];?>" title="<?php echo$partido['visitante']; ?>"><?php echo $partido['visitante']; ?></label></td>
			<td><label for="V<?php echo$partido['id_partido'];?>" title="<?php echo$partido['visitante']; ?>"><img src="../escudos/equipo<?php echo $partido['e2']; ?>.gif" alt="<?php echo$partido['visitante']; ?>" height="30" /></label></td>
			<td><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="V" id="V<?php echo$partido['id_partido'];?>" <?php if ($partido['resultado'] == "V") { echo "checked "; } if ($partido['disabled'] == "S") { echo "disabled "; } ?>/></td>
		</tr>
	<?php } ?>
		<tr>
			<td></td>
			<td colspan="7"><input type="submit" name="enviar" value="Guardar resultado" class="button" /></td>
		</tr>
	</table>
</form>
<?php } ?>
</div>
</body>
</html>