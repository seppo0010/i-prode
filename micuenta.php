<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }
if (count($_POST) > 0) {
	if (empty($_POST['recibirmail'])) { $recibirmail = "N"; } else { $recibirmail = "S"; }
	if ($_POST['pass1'] == $_POST['pass2'] AND !empty($_POST['pass1'])) { $pass = ", clave = md5('".$_POST['pass1']."')"; }
	elseif (!empty($_POST['pass1']) OR !empty($_POST['pass2'])) { $error = "pass"; }
	mysql_query("UPDATE prode_usuario SET nombre = '".$_POST['nombre']."', mail = '".$_POST['mail']."', recibirmail = '".$recibirmail."'".$pass." WHERE id_usuario = '".$_SESSION['id']."'");
	if (count($error) == 0) {
		header("location:ok.php?ok=cuenta");
		exit;
	}
}
$q_datos = mysql_query("SELECT id_usuario, u.usuario, u.mail, u.nombre, u.recibirmail, COUNT(pro.id_pronostico) AS puntos FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico AND pr.resultado = pa.resultado LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.resultado IS NOT NULL AND pa.resultado != '' AND pr.resultado IS NOT NULL AND id_usuario = '".$_SESSION['id']."' GROUP BY usuario, id_usuario ORDER BY puntos DESC");
$datos = mysql_fetch_assoc($q_datos);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Mi cuenta</title>
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
<h1>Mi cuenta</h1>
<?php if (isset($error) && $error == "pass") { ?><p class="rojo">Las contrasese&ntilde;as ingresadas no coinciden. Las dem&aacute;s modificaciones han sido realizadas</p><?php } ?>
<form action="micuenta.php" method="post">
<table cellspacing="0">
	<tr>
		<th align="right" width="50%">Nombre de usuario</th>
		<th align="left"><?php echo htmlentities($datos['usuario']);?></th>
	</tr>
	<tr>
		<th align="right">Aciertos</th>
		<th align="left"><?php echo htmlentities($datos['puntos']);?></th>
	</tr>
 	<tr>
		<td align="right">Contrase&ntilde;a</td>
		<td><input type="password" name="pass1" size="40" /></td>
	</tr>
 	<tr>
		<td align="right">Repetir contrase&ntilde;a</td>
		<td><input type="password" name="pass2" size="40" /></td>
	</tr>
 	<tr>
		<td align="right">Nombre</td>
		<td><input type="text" name="nombre" value="<?php echo htmlentities($datos['nombre']); ?>" size="40" /></td>
	</tr>
	<tr>
		<td align="right">Mail</td>
		<td><input type="text" name="mail" value="<?php echo htmlentities($datos['mail']); ?>" size="40" /></td>
	</tr>
	<tr>
		<td align="right">&iquest;Recibir mails del sitio?</td>
		<td><input type="checkbox" name="recibirmail" <?php if ($datos['recibirmail'] == "S") { echo "checked "; } ?>/></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="enviar" value="Guardar cambios" class="button" /></td>
	</tr>
</table>
</form>
</div>
</body>
</html>
