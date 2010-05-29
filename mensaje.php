<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }

if (!empty($_SESSION['admin']) && isset($_GET['accion']) && $_GET['accion'] == "eliminar") {
	mysql_query("DELETE FROM prode_mensaje WHERE id_mensaje = '".$_GET['mensaje']."'");
	header("location:mensaje.php");
	exit;
}

if (count($_POST) > 0) {
	mysql_query("INSERT INTO prode_mensaje (id_mensaje, fecha, autor, mensaje, dia) VALUES (NULL, 20, ".$_SESSION['id'].", '".$_POST['nmensaje']."', NOW())") or die(mysql_error());
	header("location:mensaje.php");
	exit;
}

if (empty($_GET['pag'])) { $_GET['pag'] = 0; }
$rows = 5;
$q_mensajes = mysql_query("SELECT id_mensaje, fecha, usuario, id_usuario, mensaje, DATE_FORMAT(dia,'%d/%m/%y %H:%i') AS diashow FROM prode_mensaje m LEFT JOIN prode_usuario u ON m.autor = u.id_usuario ORDER BY fecha DESC, dia DESC LIMIT ".($_GET['pag'] * $rows).", ".$rows."");
$q_cant_msg = mysql_query("SELECT COUNT(id_mensaje) AS cant FROM prode_mensaje WHERE fecha <= 20 AND fecha >= 1");
$cant_msg = mysql_fetch_assoc($q_cant_msg);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Mensajes</title>
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
<body onLoad="document.getElementById('nmensaje').focus();">
<?php include("inc/menu.php"); ?>
<div id="ppal">
<?php include("inc/fechas.php"); ?>
<form action="mensaje.php" method="post">
<table cellspacing="0" width="100%">
	<tr>
		<td colspan="2">
		<p>Nuevo mensaje</p>
		<p><textarea name="nmensaje" cols="60" rows="4" id="nmensaje"></textarea></p>
		<p><input type="submit" name="enviar" value="Enviar comentario" style="width:240px;" /></p>
		</td>
	</tr>
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
</table>
</form>
<?php if ($cant_msg['cant'] / $rows > 1) { ?>
<?php
$paginas = array(0,1,2,(int)$_GET['pag'] - 2,(int)$_GET['pag'] - 1,(int)$_GET['pag'],(int)$_GET['pag'] + 1,(int)$_GET['pag'] + 2,(int)ceil($cant_msg['cant'] / $rows) - 2,(int)ceil($cant_msg['cant'] / $rows) - 1,(int)ceil($cant_msg['cant'] / $rows));
$paginas = array_unique($paginas);

?>
<table>
<tr>
<td width="100">P&aacute;ginas</td>
<?php foreach ($paginas as $pag) { 
	if ($pag >= 0 AND $pag < ($cant_msg['cant'] / $rows)) { 
		if ($pag - 1 != $ant AND $pag > 1) { ?><td width="8">...</td><?php } ?>
	<td width="8"><?php if ($pag != $_GET['pag']) { ?><a href="mensaje.php?pag=<?php echo $pag; ?>"><?php } echo $pag + 1; if ($pag != $_GET['pag']) { ?></a><?php } ?></td>
	<?php $ant = $pag; } ?>
<?php } ?>
<td>&nbsp;</td>
</tr>
</table>
<?php } ?>
</div>
</body>
</html>
