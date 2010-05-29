<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }

$q_cantados = mysql_query("SELECT id_partido, fecha, date_format(dia,'%d/%m/%y') AS diashow, e1.corto AS `local`, e1.id_equipo AS e1, e2.id_equipo AS e2, e2.corto AS visitante, pa.resultado, ROUND(100 * SUM(IF(pa.resultado = pr.resultado,1,0)) / COUNT(id_partido),2) AS aciertos, COUNT(id_partido) FROM prode_partido pa LEFT JOIN prode2_pronostico pr ON pa.id_partido = pr.partido LEFT JOIN prode_equipo e1 ON pa.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON pa.visitante = e2.id_equipo WHERE pr.resultado IS NOT NULL AND pr.resultado != '' AND pa.resultado IS NOT NULL AND pa.resultado != '' GROUP BY id_partido ORDER BY aciertos DESC LIMIT 0,10");
$q_sorpresa = mysql_query("SELECT id_partido, fecha, date_format(dia,'%d/%m/%y') AS diashow, e1.corto AS `local`, e1.id_equipo AS e1, e2.id_equipo AS e2, e2.corto AS visitante, pa.resultado, ROUND(100 * SUM(IF(pa.resultado = pr.resultado,1,0)) / COUNT(id_partido),2) AS aciertos, COUNT(id_partido) FROM prode_partido pa LEFT JOIN prode2_pronostico pr ON pa.id_partido = pr.partido LEFT JOIN prode_equipo e1 ON pa.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON pa.visitante = e2.id_equipo WHERE pr.resultado IS NOT NULL AND pr.resultado != '' AND pa.resultado IS NOT NULL AND pa.resultado != '' GROUP BY id_partido ORDER BY aciertos ASC LIMIT 0,10");
$q_localista = mysql_query("SELECT u.id_usuario, u.usuario, COUNT(p.resultado) AS jugados, ROUND(100 * SUM(IF(p.resultado='L', 1, 0)) / COUNT(p.resultado),2) AS localista FROM prode_usuario u LEFT JOIN prode2_pronostico p ON u.id_usuario = p.usuario LEFT JOIN prode_partido pa ON pa.id_partido = p.partido WHERE pa.resultado IS NOT NULL AND pa.resultado != '' GROUP BY u.id_usuario, u.usuario ORDER BY localista DESC LIMIT 0,10");
$q_distribucion = mysql_query("SELECT COUNT(*) AS cant FROM prode_partido WHERE resultado = 'L' UNION ALL SELECT COUNT(*) FROM prode_partido WHERE resultado = 'E' UNION ALL SELECT COUNT(*) FROM prode_partido WHERE resultado = 'V'");
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Estad&iacute;sticas</title>
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
<h1>Distribuci&oacute;n de resultados</h1>
<table cellspacing="0">
	<tr>
		<th>Local</th>
		<th>Empate</th>
		<th>Visitante</th>
	</tr>
	<tr>
		<td align="center"><?php $distribucion = mysql_fetch_assoc($q_distribucion); echo $distribucion['cant']; ?></td>
		<td align="center"><?php $distribucion = mysql_fetch_assoc($q_distribucion); echo $distribucion['cant']; ?></td>
		<td align="center"><?php $distribucion = mysql_fetch_assoc($q_distribucion); echo $distribucion['cant']; ?></td>
	</tr>
</table>
<h1>Partidos cantados</h1>
<table cellspacing="0">
	<tr>
		<th>D&iacute;a</th>
		<th>Fecha</th>
		<th colspan="2" align="left">Local</th>
		<th colspan="2" align="right">Visitante</th>
		<th>Ganador</th>
		<th>Aciertos</th>
	</tr>
<?php while ($partido = mysql_fetch_assoc($q_cantados)) { ?>
	<tr>
		<td><?php echo $partido['diashow']; ?></td>
		<td align="center"><?php echo $partido['fecha']; ?></td>
		<td><a href="equipo.php?equipo=<?php echo $partido['e1'];?>" title="Ver m&aacute;s informaci&oacute;n de <?php echo $partido['local'];?>"><img src="escudos/equipo<?php echo $partido['e1']; ?>.gif" alt="<?php echo$partido['local']; ?>" height="30" /></a></td>
		<td align="left"><a href="equipo.php?equipo=<?php echo $partido['e1'];?>" title="Ver m&aacute;s informaci&oacute;n de <?php echo $partido['local'];?>"><?php echo $partido['local']; ?></a></td>
		<td align="right"><a href="equipo.php?equipo=<?php echo $partido['e2'];?>" title="Ver m&aacute;s informaci&oacute;n de <?php echo $partido['visitante'];?>"><?php echo $partido['visitante']; ?></a></td>
		<td><a href="equipo.php?equipo=<?php echo $partido['e1'];?>" title="Ver m&aacute;s informaci&oacute;n de <?php echo $partido['visitante'];?>"><img src="escudos/equipo<?php echo $partido['e2']; ?>.gif" alt="<?php echo$partido['visitante']; ?>" height="30" /></a></td>
		<td align="center"><?php if ("L" == $partido['resultado']) { echo '<a href="equipo.php?equipo='.$partido['e1'].'" title="Ver m&aacute;s informaci&oacute;n de '.$partido['local'].'">'.$partido['local']; } elseif ("V" == $partido['resultado']) { echo '<a href="equipo.php?equipo='.$partido['e2'].'" title="Ver m&aacute;s informaci&oacute;n de '.$partido['visitante'].'">'.$partido['visitante']; } else { echo "<a href=\"javascript:void(null)\">Empate"; } ?></a></td>
		<td align="center"><?php echo $partido['aciertos']; ?> %</td>
	</tr>
<?php } ?>
</table>

<h1>Partidos sorpresa</h1>
<table cellspacing="0">
	<tr>
		<th>D&iacute;a</th>
		<th>Fecha</th>
		<th colspan="2" align="left">Local</th>
		<th colspan="2" align="right">Visitante</th>
		<th>Ganador</th>
		<th>Aciertos</th>
	</tr>
<?php while ($partido = mysql_fetch_assoc($q_sorpresa)) { ?>
	<tr>
		<td><?php echo $partido['diashow']; ?></td>
		<td align="center"><?php echo $partido['fecha']; ?></td>
		<td><a href="equipo.php?equipo=<?php echo $partido['e1'];?>"><img src="escudos/equipo<?php echo $partido['e1']; ?>.gif" alt="<?php echo$partido['local']; ?>" height="30" /></a></td>
		<td align="left"><a href="equipo.php?equipo=<?php echo $partido['e1'];?>"><?php echo $partido['local']; ?></a></td>
		<td align="right"><a href="equipo.php?equipo=<?php echo $partido['e2'];?>"><?php echo $partido['visitante']; ?></a></td>
		<td><a href="equipo.php?equipo=<?php echo $partido['e1'];?>"><img src="escudos/equipo<?php echo $partido['e2']; ?>.gif" alt="<?php echo$partido['visitante']; ?>" height="30" /></a></td>
		<td align="center"><?php if ("L" == $partido['resultado']) { echo '<a href="equipo.php?equipo='.$partido['e1'].'" title="Ver m&aacute;s informaci&oacute;n de '.$partido['local'].'">'.$partido['local']; } elseif ("V" == $partido['resultado']) { echo '<a href="equipo.php?equipo='.$partido['e2'].'" title="Ver m&aacute;s informaci&oacute;n de '.$partido['visitante'].'">'.$partido['visitante']; } else { echo "<a href=\"javascript:void(null)\">Empate"; } ?></a></td>
		<td align="center"><?php echo $partido['aciertos']; ?> %</td>
	</tr>
<?php } ?>
</table>

<h1>Localista</h1>
<table cellspacing="0">
	<tr>
		<th align="left">Usuario</th>
		<th>Prodes locales</th>
		<th>Total jugados</th>
	</tr>
<?php while ($usuario = mysql_fetch_assoc($q_localista)) { ?>
	<tr>
		<td><a href="tablaprode.php?jugador=<?php echo $usuario['id_usuario'];?>" title="M&aacute;s informaci&oacute;n de <?php echo $usuario['usuario']; ?>"><?php echo $usuario['usuario']; ?></a></td>
		<td align="center"><a href="tablaprode.php?jugador=<?php echo $usuario['id_usuario'];?>" title="M&aacute;s informaci&oacute;n de <?php echo $usuario['usuario']; ?>"><?php echo $usuario['localista']; ?> %</a></td>
		<td align="center"><a href="tablaprode.php?jugador=<?php echo $usuario['id_usuario'];?>" title="M&aacute;s informaci&oacute;n de <?php echo $usuario['usuario']; ?>"><?php echo $usuario['jugados']; ?> partidos</a></td>
	</tr>
<?php } ?>
</table>
</div>
</body>
</html>