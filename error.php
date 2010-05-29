<?php
require 'inc/conexion.php';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Se ha producido un error</title>
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
<p style="color:#FF3333; background-color:transparent;"><?php
switch ($_GET['error']) {
  case "usuario":
	echo "El nombre de usuario o la contrase&ntilde;a ingresada es incorrecto";
	break;

  case "nousuario":
  	echo "Debe ser un <span style=\"font-style:italic;\">usuario registrado</span> para acceder a la secci&oacute;n solicitada</p><p><a href=\"registrate.php\">Reg&iacute;strese</a>.";
	break;

  case "nodb":
	echo "No se puede conectar a la base de datos</p>".chr(13)."  <p>Si necesita enviar un Pron&oacute;stico con urgencia env&iacute;e un mail a <a href='mailto:i-prode@hotmail.com'>i-prode@hotmail.com</a> antes del comienzo de los partidos especificando su nombre de usuario y su &quot;boleta de prode&quot; de esta fecha.";
	break;
	
  case "nodata":
	echo "No se puede usar la opci&oacute;n &quot;atr&aacute;s&quot; en la tabla de posiciones simulada.";
	break;
	
  default:
	echo "Se ha producido un error en la operaci&oacute;n solicitada.";
	break;
}
?></p>
</div>
</body>
</html>
