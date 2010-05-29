<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }

if (count($_POST) > 0) {
	foreach ($_POST['opcion'] as $identif => $valor) {
		mysql_query("DELETE FROM prode2_voto WHERE usuario = '".$_SESSION['id']."' AND encuesta = '".$identif."'");
		mysql_query("INSERT INTO prode2_voto (id_voto, encuesta, opcion, usuario) VALUES (NULL, '".$identif."', '".$valor."', '".$_SESSION['id']."')");
		header("location:ok.php?ok=encuesta&encuesta=".$identif);
		exit;
	}
}

if(empty($_GET['encuesta'])) {
	$q_encuestas = mysql_query("SELECT id_encuesta, pregunta, opcion1, opcion2, opcion3, opcion4, DATE_FORMAT(limite,'%d/%m/%y') AS fechashow, opcion, IF(limite < NOW(),'disabled','') AS disabled FROM prode2_encuesta e LEFT JOIN prode2_voto v ON e.id_encuesta = v.encuesta AND v.usuario = '".$_SESSION['id']."' ORDER BY to_days(limite) DESC LIMIT 0,6") or die(mysql_error());
} else {
	$q_encuesta = mysql_query("SELECT pregunta, opcion1, opcion2, opcion3, opcion4, DATE_FORMAT(limite,'%d/%m/%y') AS fechashow, SUM(IF(opcion = 1,1,0)) AS votos1, SUM(IF(opcion = 2,1,0)) AS votos2, SUM(IF(opcion = 3,1,0)) AS votos3, SUM(IF(opcion = 4,1,0)) AS votos4, COUNT(id_voto) AS total FROM prode2_encuesta e LEFT JOIN prode2_voto v ON e.id_encuesta = v.encuesta WHERE id_encuesta = '".$_GET['encuesta']."' GROUP BY id_encuesta");
	$enc = mysql_fetch_assoc($q_encuesta);
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Encuestas</title>
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
<?php if(empty($_GET['encuesta'])) { ?>
	<h1>Encuestas</h1>
	<?php while ($enc = mysql_fetch_assoc($q_encuestas)) { ?>
	<p>&nbsp;</p>
	<form action="encuesta.php" method="post">
	<table cellspacing="0">
		<tr>
			<th colspan="2" align="left"><?php echo autolink(htmlentities($enc['pregunta'])); ?></th>
		</tr>
		<tr>
			<td colspan="2">Hasta el: <?php echo $enc['fechashow']; ?></td>
		</tr>
		<tr>
			<td width="24"><input type="radio" name="opcion[<?php echo $enc['id_encuesta'] ?>]" value="1" id="encuesta<?php echo $enc['id_encuesta']; ?>opcion1" style="width:12px;height:12px;" <?php if ($enc['opcion'] == 1) { echo "checked "; } ?><?php echo $enc['disabled'] ?>/></td>
			<td><label for="encuesta<?php echo $enc['id_encuesta']; ?>opcion1"><?php echo htmlentities($enc['opcion1']); ?></label></td>
		</tr>
		<tr>
			<td><input type="radio" name="opcion[<?php echo $enc['id_encuesta'] ?>]" value="2" id="encuesta<?php echo $enc['id_encuesta']; ?>opcion2" style="width:12px;height:12px;" <?php if ($enc['opcion'] == 2) { echo "checked "; } ?><?php echo $enc['disabled'] ?>/></td>
			<td><label for="encuesta<?php echo $enc['id_encuesta']; ?>opcion2"><?php echo htmlentities($enc['opcion2']); ?></label></td>
		</tr>
		<tr>
			<td><input type="radio" name="opcion[<?php echo $enc['id_encuesta'] ?>]" value="3" id="encuesta<?php echo $enc['id_encuesta']; ?>opcion3" style="width:12px;height:12px;" <?php if ($enc['opcion'] == 3) { echo "checked "; } ?><?php echo $enc['disabled'] ?>/></td>
			<td><label for="encuesta<?php echo $enc['id_encuesta']; ?>opcion3"><?php echo htmlentities($enc['opcion3']); ?></label></td>
		</tr>
		<tr>
			<td><input type="radio" name="opcion[<?php echo $enc['id_encuesta'] ?>]" value="4" id="encuesta<?php echo $enc['id_encuesta']; ?>opcion4" style="width:12px;height:12px;" <?php if ($enc['opcion'] == 4) { echo "checked "; } ?><?php echo $enc['disabled'] ?>/></td>
			<td><label for="encuesta<?php echo $enc['id_encuesta']; ?>opcion4"><?php echo htmlentities($enc['opcion4']); ?></label></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="enviar" value="Votar" style="width:160px;" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="encuesta.php?encuesta=<?php echo $enc['id_encuesta']; ?>">Ver resultados</a></td>
		</tr>
	</table>
	</form>
	<?php }	?>
<?php } else { ?>
	<h1>Resultados de la encuesta</h1>
	<table cellspacing="0">
		<tr>
			<th width="120">Votos</th>
			<th align="justify"><?php echo $enc['pregunta']; ?></th>
		</tr>
		<tr>
			<td align="center"><?php echo $enc['votos1']; ?> (<?php echo round(100 * $enc['votos1'] / $enc['total'],2); ?> %)</td>
			<td><?php echo $enc['opcion1']; ?></td>
		</tr>
		<tr>
			<td align="center"><?php echo $enc['votos2']; ?> (<?php echo round(100 * $enc['votos2'] / $enc['total'],2); ?> %)</td>
			<td><?php echo $enc['opcion2']; ?></td>
		</tr>
		<tr>
			<td align="center"><?php echo $enc['votos3']; ?> (<?php echo round(100 * $enc['votos3'] / $enc['total'],2); ?> %)</td>
			<td><?php echo $enc['opcion3']; ?></td>
		</tr>
		<tr>
			<td align="center"><?php echo $enc['votos4']; ?> (<?php echo round(100 * $enc['votos4'] / $enc['total'],2); ?> %)</td>
			<td><?php echo $enc['opcion4']; ?></td>
		</tr>
	</table>
	<p><a href="encuesta.php">Volver a la lista de encuestas</a></p>
<?php } ?>
</div>
</body>
</html>