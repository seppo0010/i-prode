<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }

$q_equipos = mysql_query("SELECT id_equipo, corto, largo, SUM(IF(`local` = id_equipo,IF(resultado = 'L',3,IF(resultado = 'E',1,0)),0)) + SUM(IF(`visitante` = id_equipo,IF(resultado = 'V',3,IF(resultado = 'E',1,0)),0)) AS puntos FROM prode_equipo e LEFT JOIN prode_partido p ON e.id_equipo = p.local OR e.id_equipo = p.visitante GROUP BY id_equipo, corto, largo ORDER BY puntos DESC, largo ASC, corto ASC");
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Tabla de posiciones</title>
	<META HTTP-EQUIV="expires" CONTENT="Wed, 26 Feb 1997 08:21:57 GMT" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<h1>Tabla de posiciones</h1>
<table cellspacing="0">
	<tr>
		<th colspan="2" align="left">Equipo</th>
		<th>Puntos</th>
	</tr>
<?php while ($equipo = mysql_fetch_assoc($q_equipos)) { ?>
	<tr>
		<td><img src="escudos/equipo<?php echo $equipo['id_equipo']; ?>.gif" alt="<?php echo htmlentities($equipo['corto']); ?>" height="30" /></td>
		<td><a href="equipo.php?equipo=<?php echo$equipo['id_equipo'];?>" title="Ver m&aacute;s infromaci&oacute;n de <?php echo htmlentities($equipo['largo']);?>"><?php echo htmlentities($equipo['largo']);?></a></td>
		<td align="center"><?php echo$equipo['puntos'];?></td>
	</tr>
<?php } ?>
</table>
</div>
</body>
</html>
