<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }

if(empty($_GET['mostrar'])) {
	$q_noticias = mysql_query("SELECT titulo, noticia, DATE_FORMAT(fecha,'%d/%m/%y') AS fechashow FROM prode2_noticias ORDER BY to_days(fecha) DESC LIMIT 0,6") or die(mysql_error());
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Noticias</title>
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
<?php if(empty($_GET['mostrar'])) { ?>
	<h1>&Uacute;ltimas noticias</h1>
	<?php
	while ($not = mysql_fetch_assoc($q_noticias)) { 
		echo autolink("<h2>".$not['titulo']."</h2>
		<h6>".$not['fechashow']."</h6>
		<p>".nl2br($not['noticia'])."</p>");
		
	}
	?>
<?php } ?>
</div>
</body>
</html>