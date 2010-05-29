<?php
require_once("../inc/conexion.php");
if (empty($_SESSION['admin'])) { header("location:../error.php?error=nousuario"); exit; }

if (count($_POST) > 0) {
	foreach ($_POST['admin'] as $id => $admin) {
		if (!empty($admin)) {
			if (!empty($_POST['clave'][$id]) AND $_POST['clave'][$id] == $_POST['clave'][$id]) { $pass = ", clave = password('".$_POST['clave'][$id]."')"; }
			mysql_query("UPDATE prode_admin SET admin = '".$admin."'".$pass." WHERE id_admin = '".$id."'");
		} else {
			mysql_query("DELETE FROM prode_admin WHERE id_admin = '".$id."'");
		}
	}
	if (!empty($_POST['nclave']) AND !empty($_POST['nadmin']) AND $_POST['nclave'] == $_POST['nclave2']) {
		mysql_query("INSERT INTO prode_admin (id_admin, admin, clave) VALUES (NULL, '".$_POST['nadmin']."', password('".$_POST['nclave']."'))") or die(mysql_error());
	}
	header("location:admin.php");
	exit;
}

$q_admin = mysql_query("SELECT id_admin, admin FROM prode_admin") or die(mysql_error());
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Administradores</title>
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
<h1>Lista de administradores</h1>
<form action="admin.php" method="post">
<table>
<?php while ($admin = mysql_fetch_assoc($q_admin)) { ?>
	<tr>
		<td><input type="text" name="admin[<?php echo $admin['id_admin']; ?>]" value="<?php echo $admin['admin']; ?>" /></td>
		<td><input type="password" name="clave[<?php echo $admin['id_admin']; ?>]" /></td>
		<td><input type="password" name="clave2[<?php echo $admin['id_admin']; ?>]" /></td>
	</tr>
<?php } ?>
	<tr>
		<td><input type="text" name="nadmin" /></td>
		<td><input type="password" name="nclave" /></td>
		<td><input type="password" name="nclave2" /></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="7"><input type="submit" name="enviar" value="Guardar prode" class="button" /></td>
	</tr>
</table>
</form>
</div>
</body>
</html>