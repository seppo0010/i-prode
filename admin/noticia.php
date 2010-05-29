<?php
require_once("../inc/conexion.php");
if (empty($_SESSION['admin'])) { header("location:../error.php?error=nousuario"); exit; }

if (count($_POST) > 0) {
	if (count($_POST['titulo']) > 0) {
		foreach ($_POST['titulo'] as $id => $titulo) {
			if (!empty($titulo)) {
				mysql_query("UPDATE prode2_noticias SET titulo = '".$titulo."', fecha = '".$_POST['fecha'][$id]."', noticia = '".$_POST['noticia'][$id]."' WHERE id_noticia = '".$id."'");
			} else {
				mysql_query("DELETE FROM prode2_noticias WHERE id_noticia = '".$id."'");
			}
		}
	} else {
		mysql_query("INSERT INTO prode2_noticias (id_noticia, titulo, fecha, noticia) VALUES (NULL, '".$_POST['ntitulo']."', '".$_POST['nfecha']."', '".$_POST['nnoticia']."')") or die(mysql_error());
	}
	header("location:noticia.php");
	exit;
}

$rows = 5;
$q_noticias = mysql_query("SELECT id_noticia, fecha, titulo, noticia FROM prode2_noticias ORDER BY fecha DESC LIMIT ".($rows * $_GET['pag']).", ".$rows."") or die(mysql_error());
$q_cant_noticias = mysql_query("SELECT COUNT(id_noticia) AS cant FROM prode2_noticias");
$cant_not = mysql_fetch_assoc($q_cant_noticias);
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
	<link rel="Principal" title="Página principal" href="../index.php" />
	<link rel="stylesheet" href="../css/estilo.css" type="text/css" />
</head>
<body>
<?php include("inc/menu.php"); ?>
<div id="ppal">
<?php include("../inc/fechas.php"); ?>
<h1>Noticias</h1>
<h2>Nueva noticia</h2>
<form action="noticia.php" method="post">
<table>
	<tr>
		<td width="60">T&iacute;tulo:</td><td><input type="text" name="ntitulo" value="" /></td>
	</tr>
	<tr>
		<td>Fecha:</td><td><input type="text" name="nfecha" value="<?php echo date("Y-m-d"); ?>" /> (YYYY-mm-dd)</td>
	</tr>
	<tr>
		<td>Noticia:</td><td><textarea name="nnoticia" cols="60" rows="6"></textarea></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td><td><input type="submit" name="enviar" value="Crear noticia" style="width:240px;" /></td>
	</tr>
</table>
</form>

<h2>Noticias anteriores</h2>
<form action="noticia.php" method="post">
<table>
<?php while ($not = mysql_fetch_assoc($q_noticias)) { ?>
	<tr>
		<td width="60">T&iacute;tulo:</td><td><input type="text" name="titulo[<?php echo $not['id_noticia']; ?>]" value="<?php echo $not['titulo']; ?>" /></td>
	</tr>
	<tr>
		<td>Fecha:</td><td><input type="text" name="fecha[<?php echo $not['id_noticia']; ?>]" value="<?php echo $not['fecha']; ?>" /></td>
	</tr>
	<tr>
		<td>Noticia:</td><td><textarea name="noticia[<?php echo $not['id_noticia']; ?>]" cols="60" rows="6"><?php echo htmlentities($not['noticia']); ?></textarea></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
<?php } ?>
	<tr>
		<td>&nbsp;</td><td><input type="submit" name="enviar" value="Guardar cambios" style="width:240px;" /></td>
	</tr>
</table>
</form>

<p>&nbsp;</p>
<?php if ($cant_not['cant'] / $rows > 1) { ?>
<?php
$paginas = array(0,1,2,(int)$_GET['pag'] - 2,(int)$_GET['pag'] - 1,(int)$_GET['pag'],(int)$_GET['pag'] + 1,(int)$_GET['pag'] + 2,(int)ceil($cant_not['cant'] / $rows) - 2,(int)ceil($cant_not['cant'] / $rows) - 1,(int)ceil($cant_not['cant'] / $rows));
$paginas = array_unique($paginas);

?>
<table>
<tr>
<td width="100">P&aacute;ginas</td>
<?php foreach ($paginas as $pag) { 
	if ($pag >= 0 AND $pag < ($cant_not['cant'] / $rows)) { 
		if ($pag - 1 != $ant AND $pag > 1) { ?><td width="8">...</td><?php } ?>
	<td width="8"><?php if ($pag != $_GET['pag']) { ?><a href="noticia.php?pag=<?php echo $pag; ?>"><?php } echo $pag + 1; if ($pag != $_GET['pag']) { ?></a><?php } ?></td>
	<?php $ant = $pag; } ?>
<?php } ?>
<td>&nbsp;</td>
</tr>
</table>
<?php } ?>
</div>
</body>
</html>