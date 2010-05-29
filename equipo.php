<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }

$q_partidos = mysql_query("SELECT fecha, e1.corto AS local, e1.id_equipo AS e1, e2.corto AS visitante, e2.id_equipo AS e2, p.resultado FROM prode_partido p LEFT JOIN prode_equipo e1 ON p.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON p.visitante = e2.id_equipo WHERE (e1.id_equipo = '".$_GET['equipo']."' OR e2.id_equipo = '".$_GET['equipo']."') ORDER BY fecha ASC");
$q_equipos = mysql_query("SELECT id_equipo, corto, largo, SUM(IF(`local` = id_equipo,IF(resultado = 'L',3,IF(resultado = 'E',1,0)),0)) + SUM(IF(`visitante` = id_equipo,IF(resultado = 'V',3,IF(resultado = 'E',1,0)),0)) AS puntos FROM prode_equipo e LEFT JOIN prode_partido p ON e.id_equipo = p.local OR e.id_equipo = p.visitante WHERE id_equipo = '".$_GET['equipo']."' GROUP BY id_equipo, corto, largo ORDER BY puntos DESC, largo ASC, corto ASC");
$equipo = mysql_fetch_assoc($q_equipos);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Datos de <?php echo $equipo['corto']; ?></title>
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
<h1><?php echo$equipo['largo'];?></h1>
<table cellspacing="0">
	<tr>
		<th>Fecha</th>
		<th colspan="2" align="left">Contra</th>
		<th align="left">Condici&oacute;n</th>
		<th>Ganador</th>
	</tr>
<?php while ($partido = mysql_fetch_assoc($q_partidos)) { ?>
	<tr>
		<td align="center"><?php echo$partido['fecha'];?></td>
		<td><a href="equipo.php?equipo=<?php if ($partido['local'] == $equipo['corto']) { echo $partido['e2']; } else { echo $partido['e1']; } ?>"><img src="escudos/equipo<?php if ($partido['local'] == $equipo['corto']) { echo $partido['e2']; } else { echo $partido['e1']; } ?>.gif" alt="<?php echo htmlentities($equipo['corto']); ?>" height="30"  /></a></td>
		<td><a href="equipo.php?equipo=<?php if ($partido['local'] == $equipo['corto']) { echo $partido['e2']; } else { echo $partido['e1']; } ?>"><?php if ($partido['local'] == $equipo['corto']) { echo $partido['visitante']; } else { echo $partido['local']; } ?></a></td>
		<td><?php if ($partido['local'] == $equipo['corto']) { echo "Local"; } else { echo "Visitante"; } ?></td>
		<td align="center"><?php switch ($partido['resultado']) {
			case "L": echo $partido['local']; break;
			case "E": echo "Empate"; break;
			case "V": echo $partido['visitante']; break;
		} ?></td>
	</tr>
<?php } ?>
</table>
</div>
</body>
</html>