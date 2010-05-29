<?php
require_once("../inc/conexion.php");
if (empty($_SESSION['admin'])) { header("location:../error.php?error=nousuario"); exit; }

if (count($_POST) > 0) {
	$_SESSION['id'] = $_POST['usuario'];
	header("location:../index.php");
	exit;
}

$q_usuarios = mysql_query("SELECT id_usuario, usuario, nombre FROM prode_usuario") or die(mysql_error());
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Usuarios</title>
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
<h1>Lista de usuarios</h1>
<form action="usuarios.php" method="post">
<table>
<?php while ($usuario = mysql_fetch_assoc($q_usuarios)) { ?>
	<form action="usuarios.php" method="post">
	<tr>
		<td><?php echo $usuario['usuario']; ?></td>
		<td><?php echo $usuario['nombre']; ?></td>
		<td><input type="submit" name="enviar" value="Usar cuenta" /></td>
	</tr>
	<input type="hidden" name="usuario" value="<?php echo $usuario['id_usuario']; ?>" />
	</form>
<?php } ?>
</table>
</form>
</div>
</body>
</html>