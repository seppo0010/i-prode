<?php
require_once("inc/conexion.php");
if (count($_POST) > 0) {
	require("admin/mailer/class.phpmailer.php");
	ignore_user_abort(true);
	$q_usuario = mysql_query("SELECT usuario, mail FROM prode_usuario WHERE id_usuario = '".$_SESSION['id']."'");
	$usuario = mysql_fetch_assoc($q_usuario);
	$mail = new PHPMailer();
	$mail->SetLanguage("es");
	$mail->Host = "localhost"; // SMTP server
	$mail->From = "seppo0010@gmail.com";
	$mail->FromName = "Prode";
	$mail->Subject = "Sugerencia enviada desde la web";
	$mail->IsHTML(false);
	$mail->Body = "Mensaje de ".$usuario['usuario']." ( ".$usuario['mail']."):\r\n".$_POST['sugerencia'];
	$mail->AddAddress("seppo0010@gmail.com");
	if($mail->Send()) {
		header("location:ok.php?ok=sugerencia");
	} else {
		header("location:error.php");
	}
	exit;
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Sugerencias</title>
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
<body onLoad="document.getElementById('sugerencia').focus()">
<?php include("inc/menu.php"); ?>
<div id="ppal">
<?php include("inc/fechas.php"); ?>
<h1>Sugerencias</h1>
<p>Desde aqu&iacute; puede ayudarnos a mejorar el Prode con las propuestas que tenga para su desarrollo.</p>
<form action="sugerencia.php" method="post">
<p><textarea name="sugerencia" cols="60" rows="6" id="sugerencia"></textarea></p>
<p><input type="submit" name="enviar" value="Enviar sugerencia" style="width:240px;" /></p>
</form>
</div>
</body>
</html>
