<?php
require_once("inc/conexion.php");
if (isset($_GET['accion']) && $_GET['accion'] == "salir") {
  session_destroy();
  header("location:index.php");
  exit;
}

if (count($_POST) > 0) {
  foreach ($_POST as $campo => $valor) {
	$_POST[$campo] = mysql_real_escape_string(trim($valor));
  }
  if (!isset($_POST['accion']) || $_POST['accion'] != "registrarse") {
	  $q_usuario = mysql_query("SELECT id_usuario, a.id_admin FROM prode_usuario u LEFT JOIN prode_admin a ON u.id_usuario = a.usuario WHERE u.usuario = '".$_POST['usuario']."' AND u.clave = md5('".$_POST['clave']."')") or diE(mysql_error());
	  if (mysql_num_rows($q_usuario) > 0) {
		$usuario = mysql_fetch_assoc($q_usuario);
	  	mysql_query("UPDATE prode_usuario SET ulogin = NOW() WHERE id_usuario = '".$usuario['id_usuario']."'");
	  	$_SESSION['id'] = $usuario['id_usuario'];
	  	if (!empty($usuario['id_admin'])) {
	  		$_SESSION['admin'] = $usuario['id_admin'];
	  	}
		header("location:noticias.php");
	  } else {
		header("location:error.php?error=usuario");
	  }
	  exit;
  } else {
	$q_cant_usuarios = mysql_query("SELECT COUNT(*) AS cant FROM prode_usuario WHERE usuario = '".$_POST['usuario']."'");
	$cant_usuarios = mysql_fetch_assoc($q_cant_usuarios);
	if (isset($_POST['pass1']) && ($_POST['pass1'] != $_POST['pass2'] OR empty($_POST['pass1']))) {
		$error[] = "pass";
	}
	if ($cant_usuarios['cant'] > 0 AND !empty($_POST['usuario'])) {
		$error[] = "usuario";
	}
	if (count($error) == 0) {
		if (empty($_POST['recibirmail'])) { $recibirmail = "N"; } else { $recibirmail = "S"; }
		mysql_query("INSERT INTO prode_usuario (id_usuario, usuario, mail, clave, nombre, recibirmail) VALUES (NULL, '".$_POST['usuario']."', '".$_POST['mail']."', md5('".$_POST['pass1']."'), '".$_POST['nombre']."', '".$recibirmail."')");
		$_SESSION['id'] = mysql_insert_id();
		header("location:noticias.php");
		exit;
	}
  }
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - P&aacute;gina principal</title>
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
<div style="width:74%; margin:0;padding:0;margin-left:23%;">
<?php if (isset($_GET['opcion']) && $_GET['opcion'] == "registrarse") { ?>
<h1>Bienvenido</h1>
<h2>Si no posee una cuenta, desde aqu&iacute; podr&aacute; crear una</h2>
<?php if (isset($error) && count($error) > 0) {
if (in_array("pass",$error)) { echo "<p class=\"rojo\">La contrase&ntilde;a ingresada no es v&aacute;lida o no son iguales</p>"; }
if (in_array("usuario",$error)) { echo "<p class=\"rojo\">El nombre de usuario ingresado es incorrecto o est&aacute; en uso</p>"; }
} ?>
<form action="index.php?opcion=registrarse" method="post">
<table cellspacing="0">
	<tr>
		<th colspan="2">Complete los siguientes datos</th>
	</tr>
	<tr>
		<td align="right" width="50%">Nombre de usuario:</td>
		<td><input type="text" name="usuario" value="<?php echo isset($_POST['usuario']) ? $_POST['usuario'] : '';?>" /></td>
	</tr>
	<tr>
		<td align="right">Nombre:</td>
		<td><input type="text" name="nombre" value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : '';?>" /></td>
	</tr>
	<tr>
		<td align="right">Contrase&ntilde;a:</td>
		<td><input type="password" name="pass1" value="" /></td>
	</tr>
	<tr>
		<td align="right">Repetir contrase&ntilde;a:</td>
		<td><input type="password" name="pass2" value="" /></td>
	</tr>
	<tr>
		<td align="right">Mail:</td>
		<td><input type="text" name="mail" value="<?php echo isset($_POST['mail']) ? $_POST['mail'] : '';?>" /></td>
	</tr>
	<tr>
		<td align="right">&iquest;Desea recibir mails semanales con datos de la fecha?</td>
		<td><input type="checkbox" name="recibirmail" <?php if (!empty($_POST['recibirmail'])) { echo "checked='checked' "; } ?>/></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="enviar" value="Crear cuenta" class="button" /></td>
	</tr>
</table>
<input type="hidden" name="accion" value="registrarse" />
</form>
<?php } else { ?>
<div>
<h1>Prode</h1>
<p>Jugar prode es muy simple. S&oacute;lamente debes hacer clic en <a href="index.php?opcion=registrarse">Registrarse</a> y llenar los datos. Luego podr&aacute;s entrar con tu usuario y contrase&ntilde;a para tratar de pron&oacute;sticar los resultados del torneo de Primera Divisi&oacute;n del F&uacute;tbol Argentino.</p>
<p>En cada fecha se sumar&aacute;n los aciertos de cada jugador y el que tenga la mayor cantidad de puntos al finalizar el torneo, ser&aacute; el ganador.</p>
</div>
<?php } ?>
</div>
</body>
</html>
