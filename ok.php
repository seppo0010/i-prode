<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Informaci&oacute;n recibida</title>
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
<?php
switch ($_GET['ok']) {
  case "usuario":
	echo "<h2>e ha producido un error</h2><p class=\"rojo\">El nombre de usuario o la contrase&ntilde;a ingresada es incorrecto.</p>";
	break;
	
  case "prode":
	?><p class="amarillo">Se han guardado los pron&oacute;sticos enviados.</p>
	<script language="javascript" type="text/javascript">
	var segundos=3;
	
	var direccion='fecha.php?fecha=<?php echo$_GET['fecha']; ?>';
	
	milisegundos=segundos*1000
	window.setTimeout("window.location.replace(direccion);",milisegundos);
	document.write('<p>En tres segundos ser&aacute; redireccionado de regreso a la p&aacute;gina de pron&oacute;sticos</p>');
	</script>
	<noscript>
	<p><a href="fecha.php?fecha=<?php echo$_GET['fecha']; ?>">Volver</a></p>
	</noscript>
	<?php
	break;

  case "encuesta":
	?><p class="amarillo">Se ha guardado su voto. Gracias por participar en la encuesta.</p>
	<script language="javascript" type="text/javascript">
	var segundos=3;
	
	var direccion='encuesta.php?encuesta=<?php echo $_GET['encuesta'];?>';
	
	milisegundos=segundos*1000
	window.setTimeout("window.location.replace(direccion);",milisegundos);
	document.write('<p>En tres segundos ser&aacute; redireccionado a los resultados de la encuesta.</p>');
	</script>
	<noscript>
	<p><a href="encuesta.php?encuesta=<?php echo $_GET['encuesta'];?>">Ver los resultados de la encuesta</a></p>
	</noscript>
	<?php
	break;

  case "cuenta":
	?><p class="amarillo">Se han guardado datos de su cuenta.</p>
	<script language="javascript" type="text/javascript">
	var segundos=3;
	
	var direccion='micuenta.php';
	
	milisegundos=segundos*1000
	window.setTimeout("window.location.replace(direccion);",milisegundos);
	document.write('<p>En tres segundos ser&aacute; redireccionado de regreso a la informaci&oacute;n de su cuenta</p>');
	</script>
	<noscript>
	<p><a href="micuenta.php">Volver</a></p>
	</noscript>
	<?php
	break;

  case "sugerencia":
	?><p class="amarillo">Se ha enviado la sugerencia.</p>
	<script language="javascript" type="text/javascript">
	var segundos=3;
	
	var direccion='noticias.php';
	
	milisegundos=segundos*1000
	window.setTimeout("window.location.replace(direccion);",milisegundos);
	document.write('<p>En tres segundos ser&aacute; redireccionado a la p&aacute;gina de noticias.</p>');
	</script>
	<noscript>
	<p><a href="noticias.php">Volver</a></p>
	</noscript>
	<?php
	break;

  default:
	echo "Se ha procesado correctamente la informaci&oacute;n enviada.";
	break;
}
?>
</div>
</body>
</html>
