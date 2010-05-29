<?php
require_once("../inc/conexion.php");
if (empty($_SESSION['admin'])) { header("location:../error.php?error=nousuario"); exit; }

if (count($_POST) > 0) {
	if (count($_POST['pregunta']) > 0) {
		foreach ($_POST['pregunta'] as $id => $pregunta) {
			if (!empty($_POST['limite'][$id])) {
				mysql_query("UPDATE prode2_encuesta SET pregunta = '".$pregunta."', opcion1 = '".$_POST['opcion1'][$id]."', opcion2 = '".$_POST['opcion2'][$id]."', opcion3 = '".$_POST['opcion3'][$id]."', opcion4 = '".$_POST['opcion4'][$id]."', limite = '".$_POST['limite'][$id]."' WHERE id_encuesta = '".$id."'") or die(mysql_error());
			} else {
				mysql_query("DELETE FROM prode2_encuesta WHERE id_encuesta = '".$id."'");
			}
		}
	} elseif (!empty($_POST['nlimite'])) {
		mysql_query("INSERT INTO prode2_encuesta (id_encuesta, pregunta, opcion1, opcion2, opcion3, opcion4, limite) VALUES (NULL, '".$_POST['npregunta']."', '".$_POST['nopcion1']."', '".$_POST['nopcion2']."', '".$_POST['nopcion3']."', '".$_POST['nopcion4']."', '".$_POST['nlimite']."')");
	}
	header("location:encuesta.php");
	exit;
}

$rows = 1;
$q_encuestas = mysql_query("SELECT id_encuesta, pregunta, opcion1, opcion2, opcion3, opcion4, limite FROM prode2_encuesta ORDER BY limite DESC LIMIT ".($rows * $_GET['pag']).", ".$rows."") or die(mysql_error());
$q_cant_enc = mysql_query("SELECT COUNT(id_encuesta) AS cant FROM prode2_encuesta");
$cant_enc = mysql_fetch_assoc($q_cant_enc);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Encuestas</title>
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
<h1>Encuestas</h1>
<h2>Nueva encuesta</h2>
<form action="encuesta.php" method="post">
<table>
	<tr>
		<td width="60">Fecha l&iacute;mite:</td><td><input type="text" name="nlimite" /> (YYYY-mm-dd)</td>
	</tr>
	<tr>
		<td>Pregunta:</td><td><textarea name="npregunta" cols="60" rows="6"></textarea></td>
	</tr>
	<tr>
		<td>Opci&oacute;n 1:</td><td><input type="text" name="nopcion1" /></td>
	</tr>
	<tr>
		<td>Opci&oacute;n 2:</td><td><input type="text" name="nopcion2" /></td>
	</tr>
	<tr>
		<td>Opci&oacute;n 3:</td><td><input type="text" name="nopcion3" /></td>
	</tr>
	<tr>
		<td>Opci&oacute;n 4:</td><td><input type="text" name="nopcion4" /></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td><td><input type="submit" name="enviar" value="Guardar cambios" style="width:240px;" /></td>
	</tr>
</table>
</form>

<h2>Encuestas anteriores</h2>
<form action="encuesta.php" method="post">
<table>
<?php while ($enc = mysql_fetch_assoc($q_encuestas)) { ?>
	<tr>
		<td width="60">Fecha l&iacute;mite:</td><td><input type="text" name="limite[<?php echo $enc['id_encuesta']; ?>]" value="<?php echo $enc['limite']; ?>" /></td>
	</tr>
	<tr>
		<td>Pregunta:</td><td><textarea name="pregunta[<?php echo $enc['id_encuesta']; ?>]" cols="60" rows="6"><?php echo htmlentities($enc['pregunta']); ?></textarea></td>
	</tr>
	<tr>
		<td>Opci&oacute;n 1:</td><td><input type="text" name="opcion1[<?php echo $enc['id_encuesta']; ?>]" value="<?php echo $enc['opcion1']; ?>" /></td>
	</tr>
	<tr>
		<td>Opci&oacute;n 2:</td><td><input type="text" name="opcion2[<?php echo $enc['id_encuesta']; ?>]" value="<?php echo $enc['opcion2']; ?>" /></td>
	</tr>
	<tr>
		<td>Opci&oacute;n 3:</td><td><input type="text" name="opcion3[<?php echo $enc['id_encuesta']; ?>]" value="<?php echo $enc['opcion3']; ?>" /></td>
	</tr>
	<tr>
		<td>Opci&oacute;n 4:</td><td><input type="text" name="opcion4[<?php echo $enc['id_encuesta']; ?>]" value="<?php echo $enc['opcion4']; ?>" /></td>
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
<?php if ($cant_enc['cant'] / $rows > 1) { ?>
<?php
$paginas = array(0,1,2,(int)$_GET['pag'] - 2,(int)$_GET['pag'] - 1,(int)$_GET['pag'],(int)$_GET['pag'] + 1,(int)$_GET['pag'] + 2,(int)ceil($cant_enc['cant'] / $rows) - 2,(int)ceil($cant_enc['cant'] / $rows) - 1,(int)ceil($cant_enc['cant'] / $rows));
$paginas = array_unique($paginas);
?>
<table>
<tr>
<td width="100">P&aacute;ginas</td>
<?php foreach ($paginas as $pag) { 
	if ($pag >= 0 AND $pag < ($cant_enc['cant'] / $rows)) { 
		if ($pag - 1 != $ant AND $pag > 1) { ?><td width="8">...</td><?php } ?>
	<td width="8"><?php if ($pag != $_GET['pag']) { ?><a href="encuesta.php?pag=<?php echo $pag; ?>"><?php } echo $pag + 1; if ($pag != $_GET['pag']) { ?></a><?php } ?></td>
	<?php $ant = $pag; } ?>
<?php } ?>
<td>&nbsp;</td>
</tr>
</table>
<?php } ?>
</div>
</body>
</html>