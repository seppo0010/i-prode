<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }
if (isset($_GET['accion']) && $_GET['accion'] == "eliminar") {
	mysql_query("DELETE FROM prode_correo WHERE receptor = '".$_SESSION['id']."' AND id_correo = '".$_GET['correo']."'");
	header("location:correo.php");
	exit;
}

if (empty($_GET['correo'])) {
	$q_correo = mysql_query("SELECT leido, id_correo, usuario, titulo, DATE_FORMAT(fecha,'%d/%m/%y') AS dia FROM prode_correo c LEFT JOIN prode_usuario u ON c.emisor = u.id_usuario WHERE receptor = '".$_SESSION['id']."' ORDER BY fecha DESC");
} else {
	mysql_query("UPDATE prode_correo SET leido = 'S' WHERE receptor = '".$_SESSION['id']."' AND id_correo = '".$_GET['correo']."'");
	$q_correo = mysql_query("SELECT id_usuario, usuario, titulo, DATE_FORMAT(fecha,'%d/%m/%y') AS dia, mensaje FROM prode_correo c LEFT JOIN prode_usuario u ON c.emisor = u.id_usuario WHERE receptor = '".$_SESSION['id']."' AND id_correo = '".$_GET['correo']."'");
	$correo = mysql_fetch_assoc($q_correo);
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Correo</title>
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
<?php if (empty($_GET['correo'])) { ?>
<h1>Correo</h1>
	<?php if (mysql_num_rows($q_correo) == 0) { ?>
	<h2>No tiene correo</h2>
	<?php } else { ?>
		<table cellspacing="0">
			<tr>
				<th align="left">De</th>
				<th align="left">Asunto</th>
				<th align="left">Fecha</th>
			</tr>
		<?php while ($correo = mysql_fetch_assoc($q_correo)) { ?>
			<tr>
				<td><a href="correo.php?correo=<?php echo$correo['id_correo'];?>" title="Ver correo"><?php echo$correo['usuario'];?></a><?php 
				if ($correo['leido'] == "N") { echo ' <span style="color:red;font-size:xx-small; font-weight:bold">NUEVO</span>'; }
				?></td>
				<td><a href="correo.php?correo=<?php echo$correo['id_correo'];?>" title="Ver correo"><?php echo$correo['titulo'];?></a></td>
				<td><a href="correo.php?correo=<?php echo$correo['id_correo'];?>" title="Ver correo"><?php echo$correo['dia'];?></a></td>
			</tr>
		<?php } ?>
		</table>
	<?php } ?>
<?php } else { ?>
<h1>Correo de <?php echo$correo['usuario'];?> (<?php echo$correo['dia'];?>)</h1>
<h2><?php echo$correo['titulo'];?></h2>
<p><?php echo htmlentities($correo['mensaje']);?></p>
<p>&nbsp;</p>
<p><a href="tablaprode.php?jugador=<?php echo$correo['id_usuario'];?>#mensaje" title="Responder el mensaje">Responder</a></p>
<p><a href="correo.php?correo=<?php echo$_GET['correo'];?>&amp;accion=eliminar" title="Borrar el correo">Eliminar</a></p>
<?php } ?>
</div>
</body>
</html>
