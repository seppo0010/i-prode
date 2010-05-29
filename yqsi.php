<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }

if (count($_POST) > 0) {
	mysql_query("UPDATE prode_partido SET temp = resultado");
	foreach ($_POST['partido'] as $p => $a) {
		mysql_query("UPDATE prode_partido SET temp = '".$a."' WHERE id_partido = '".$p."'");
	}
	header("location:tablaprode.php?simular=true");
	exit;
} else {
	$q_partidos = mysql_query("SELECT e1.corto AS local, e1.id_equipo AS e1, e2.corto AS visitante, e2.id_equipo AS e2, id_partido, date_format(dia,'%d/%m/%y %H:%i') AS diashow, p.resultado FROM prode_partido p LEFT JOIN prode_equipo e1 ON p.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON p.visitante = e2.id_equipo LEFT JOIN prode2_pronostico pr ON p.id_partido = pr.partido AND usuario = '".$_SESSION['id']."' WHERE TO_DAYS(dia) <= TO_DAYS(NOW()) ORDER BY TO_DAYS(dia) DESC LIMIT 0,20");
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Y que si...</title>
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
<?php if (count($_POST) > 0) { ?>

<?php } else { ?>
<h1>&Uacute;ltimos <?php echo mysql_num_rows($q_partidos); ?> partidos</h1>
<form action="yqsi.php" method="post">
	<table>
	<?php while ($partido = mysql_fetch_assoc($q_partidos)) { ?>
		<tr>
			<td><?php echo $partido['diashow']; ?></td>
			<td align="right"><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="L" id="L<?php echo$partido['id_partido'];?>" <?php if ($partido['resultado'] == "L") { echo "checked "; } ?>/></td>
			<td><label for="L<?php echo$partido['id_partido'];?>" title="<?php echo$partido['local']; ?>"><img src="escudos/equipo<?php echo $partido['e1']; ?>.gif" alt="<?php echo$partido['local']; ?>" height="30" /></label></td>
			<td align="right"><label for="L<?php echo$partido['id_partido'];?>" title="<?php echo$partido['local']; ?>"><?php echo $partido['local']; ?></label></td>
			<td align="center"><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="E" <?php if ($partido['resultado'] == "E") { echo "checked "; } ?>/></td>
			<td><label for="V<?php echo$partido['id_partido'];?>" title="<?php echo$partido['visitante']; ?>"><?php echo $partido['visitante']; ?></label></td>
			<td><label for="V<?php echo$partido['id_partido'];?>" title="<?php echo$partido['visitante']; ?>"><img src="escudos/equipo<?php echo $partido['e2']; ?>.gif" alt="<?php echo$partido['visitante']; ?>" height="30" /></label></td>
			<td><input type="radio" name="partido[<?php echo$partido['id_partido'];?>]" value="V" id="V<?php echo$partido['id_partido'];?>" <?php if ($partido['resultado'] == "V") { echo "checked "; } ?>/></td>
		</tr>
	<?php } ?>
		<tr>
			<td></td>
			<td colspan="7"><input type="submit" name="enviar" value="Simular prode" class="button" /></td>
		</tr>
	</table>
</form>
<?php } ?>

</div>
</body>
</html>
