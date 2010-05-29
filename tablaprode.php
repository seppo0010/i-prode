<?php
require_once("inc/conexion.php");
if (empty($_SESSION['id'])) { header("location:error.php?error=timeout"); exit; }

if (count($_POST) > 0) {
	mysql_query("INSERT INTO prode_correo (id_correo, emisor, receptor, titulo, mensaje, fecha) VALUES (NULL, '".$_SESSION['id']."', '".$_GET['jugador']."', '".$_POST['titulo']."', '".$_POST['mensaje']."', NOW())");
	header("location:tablaprode.php?jugador=".$_GET['jugador']);
	exit;
}

if (empty($_GET['pag'])) $_GET['pag'] = 0;
if (empty($_GET['jugador'])) {
	if (empty($_GET['simular'])) {
		if (empty($_GET['pag'])) {$_GET['pag'] = 0; }
		$rows = 10;
		$q_jugadores = mysql_query("SELECT id_usuario, u.usuario, COUNT(pro.id_pronostico) AS puntos, ROUND(100 * COUNT(pro.id_pronostico) / COUNT(pr.id_pronostico),2) AS promedio FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico AND pr.resultado = pa.resultado LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.resultado IS NOT NULL AND pa.resultado != '' AND pr.resultado IS NOT NULL AND pr.resultado != '' GROUP BY usuario, id_usuario ORDER BY puntos DESC LIMIT ".($_GET['pag'] * $rows).", 10");
//		$q_jugador = mysql_query("SELECT id_usuario, u.usuario, COUNT(pro.id_pronostico) AS puntos, ROUND(100 * COUNT(pro.id_pronostico) / COUNT(pr.id_pronostico),2) AS promedio FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico AND pr.resultado = pa.resultado LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.resultado IS NOT NULL AND pa.resultado != '' AND pr.resultado IS NOT NULL AND id_usuario = '".$_SESSION['id']."' GROUP BY usuario, id_usuario ORDER BY puntos DESC");
                $q_jugador = mysql_query("SELECT id_usuario, u.usuario, COUNT(pro.id_pronostico) AS puntos, ROUND(100 * COUNT(pro.id_pronostico) / COUNT(pr.id_pronostico),2) AS promedio FROM prode_usuario u LEFT JOIN prode2_pronostico pr ON pr.usuario = u.id_usuario LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico AND pr.resultado = pa.resultado WHERE pa.resultado IS NOT NULL AND pa.resultado != '' AND pr.resultado IS NOT NULL AND pr.resultado != '' AND id_usuario = '".$_SESSION['id']."' GROUP BY usuario, id_usuario");
		$tusdatos = mysql_fetch_assoc($q_jugador);
		if(empty($tusdatos['puntos'])) { $tusdatos['puntos'] = 0; }
		$q_posicion = mysql_query("SELECT pr.usuario, SUM(IF(pr.resultado = pa.resultado,1,0)) AS todo FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido GROUP BY pr.usuario HAVING todo > ".$tusdatos['puntos']." ORDER BY todo DESC");
		$q_cant_usu = mysql_query("SELECT COUNT(id_usuario) AS cant FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.resultado IS NOT NULL AND pa.resultado != '' AND pr.resultado IS NOT NULL AND pr.resultado != ''") or die(mysql_error());
		if (mysql_num_rows($q_cant_usu) > 0) { $cant_usu = mysql_fetch_assoc($q_cant_usu); }
	} else {
		$rows = 10;
		$q_jugadores = mysql_query("SELECT id_usuario, u.usuario, COUNT(pro.id_pronostico) AS puntos, ROUND(100 * COUNT(pro.id_pronostico) / COUNT(pr.id_pronostico),2) AS promedio FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico AND pr.resultado = pa.temp LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.temp IS NOT NULL AND pa.temp != '' AND pr.resultado IS NOT NULL AND pr.resultado != '' GROUP BY usuario, id_usuario ORDER BY puntos DESC LIMIT 0, 10") or die(mysql_error());
		if (mysql_num_rows($q_jugadores) == 0) { header("location:error.php?error=nodata"); exit; }
		$q_jugador = mysql_query("SELECT id_usuario, u.usuario, COUNT(pro.id_pronostico) AS puntos, ROUND(100 * COUNT(pro.id_pronostico) / COUNT(pr.id_pronostico),2) AS promedio FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico AND pr.resultado = pa.temp LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.temp IS NOT NULL AND pa.temp != '' AND pr.resultado IS NOT NULL AND id_usuario = '".$_SESSION['id']."' GROUP BY usuario, id_usuario ORDER BY puntos DESC");
		$tusdatos = mysql_fetch_assoc($q_jugador);
		if(empty($tusdatos['puntos'])) { $tusdatos['puntos'] = 0; }
		$q_posicion = mysql_query("SELECT pr.usuario, SUM(IF(pr.resultado = pa.temp,1,0)) AS todo FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido GROUP BY pr.usuario HAVING todo > ".$tusdatos['puntos']." ORDER BY todo DESC");		mysql_query("UPDATE prode_partido SET temp = NULL");
		$q_cant_usu = mysql_query("SELECT COUNT(id_usuario) AS cant FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.resultado IS NOT NULL AND pa.resultado != '' AND pr.resultado IS NOT NULL AND pr.resultado != ''") or die(mysql_error());
		if (mysql_num_rows($q_cant_usu) > 0) { $cant_usu = mysql_fetch_assoc($q_cant_usu); }
	}
} else {
	$q_jugador = mysql_query("SELECT u.usuario, nombre, COUNT(id_pronostico) AS puntos FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido AND pr.resultado = pa.resultado LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.resultado IS NOT NULL AND id_usuario = '".$_GET['jugador']."' GROUP BY usuario, id_usuario");
	$jugador = mysql_fetch_assoc($q_jugador);
	if (empty($_GET['fecha'])) {
		$q_fecha = mysql_query("SELECT MAX(fecha) AS fecha FROM prode_partido WHERE dia <= NOW()");
		$fecha = mysql_fetch_assoc($q_fecha);
		$_GET['fecha'] = $fecha['fecha'];
	}
	$q_partidos = mysql_query("SELECT e1.corto AS local, e1.id_equipo AS e1, e2.corto AS visitante, e2.id_equipo AS e2, id_partido, date_format(dia,'%d/%m/%y %H:%i') AS diashow, IF(NOW() >= p.dia,pr.resultado,'') AS prode, p.resultado FROM prode_partido p LEFT JOIN prode_equipo e1 ON p.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON p.visitante = e2.id_equipo LEFT JOIN prode2_pronostico pr ON usuario = '".$_GET['jugador']."' AND p.id_partido = pr.partido WHERE fecha = '".$_GET['fecha']."' ORDER BY dia ASC, id_partido DESC") or die(mysql_error());

}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Prode - Posiciones del prode</title>
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
<?php if (empty($_GET['jugador'])) { 
	if (mysql_num_rows($q_cant_usu) > 0 AND $cant_usu['cant'] > 0) {
		if (empty($_GET['simular'])) { ?>
	<h1>Tabla de posiciones Prode</h1>
	<?php } else { ?>
	<h1>Tabla de posiciones simulada</h1>
	<?php } ?>
	<table cellspacing="0">
		<tr>
			<th colspan="2" align="left">Usuario</th>
			<th align="center">Puntos</th>
			<th align="left">Promedio</th>
		</tr>
	<?php $posicion = $rows * $_GET['pag']; while ($jugador = mysql_fetch_assoc($q_jugadores)) { $posicion += 1; ?>
		<tr>
			<td width="10"><?php echo $posicion; ?></td>
			<td><a href="tablaprode.php?jugador=<?php echo$jugador['id_usuario'];?>" title="Ver m&aacute;s informaci&oacute;n de este usuario"><?php echo$jugador['usuario'];?></a></td>
			<td align="center"><?php echo$jugador['puntos'];?></td>
			<td><?php echo number_format($jugador['promedio'],2,",",".");?> %</td>
		</tr>
	<?php } ?>
		<tr>
			<td colspan="4">
				<?php if (empty($_GET['simular']) AND $cant_usu['cant'] / $rows > 1) { ?>
				<?php
				$paginas = array(0,1,2,(int)$_GET['pag'] - 2,(int)$_GET['pag'] - 1,(int)$_GET['pag'],(int)$_GET['pag'] + 1,(int)$_GET['pag'] + 2,(int)ceil($cant_usu['cant'] / $rows) - 2,(int)ceil($cant_usu['cant'] / $rows) - 1,(int)ceil($cant_usu['cant'] / $rows));
				$paginas = array_unique($paginas);
				?>
				<table>
				<tr>
				<td width="100">P&aacute;ginas</td>
				<?php foreach ($paginas as $pag) { 
					if ($pag >= 0 AND $pag < floor($cant_usu['cant'] / $rows)) { 
						if ($pag - 1 != $ant AND $pag > 1) { ?><td width="8">...</td><?php } ?>
					<td width="8"><?php if ($pag != $_GET['pag']) { ?><a href="tablaprode.php?pag=<?php echo $pag; ?>"><?php } echo $pag + 1; if ($pag != $_GET['pag']) { ?></a><?php } ?></td>
					<?php $ant = $pag; } ?>
				<?php } ?>
				<td>&nbsp;</td>
				</tr>
				</table>
				<?php } ?>
			</td>
		</tr>
		<?php if (isset($tusdatos['usuario'])) { ?>
		<tr>
			<td colspan="4" style="background-Color:#254095"><h2>Tu posici&oacute;n</h2></td>
		</tr>
			<tr>
				<th colspan="2" align="left">Usuario</th>
				<th align="center">Puntos</th>
				<th align="left">Promedio</th>
			</tr>
			<tr>
				<td width="10"><?php echo mysql_num_rows($q_posicion) + 1; ?></td>
				<td><a href="tablaprode.php?jugador=<?php echo $tusdatos['id_usuario'];?>" title="Ver m&aacute;s informaci&oacute;n de este usuario"><?php echo $tusdatos['usuario'];?></a></td>
				<td align="center"><?php echo$tusdatos['puntos'];?></td>
				<td><?php echo number_format($tusdatos['promedio'],2,",",".");?> %</td>
			</tr>
		<?php } ?>
	</table>
<?php } else { echo "<h2>No se registran usuarios con puntuaci&oacute;n</h2>"; }
} else { ?>
	<h1><?php echo htmlentities($jugador['usuario']); ?> (<?php echo$jugador['puntos'];?> aciertos)</h1>
	<h2><?php echo htmlentities($jugador['nombre']);?> - Fecha <?php echo $_GET['fecha']; ?></h2>
	<div>
		<table border="0" cellspacing="0" class="fechas">
			<tr>
				<th colspan="20" align="center">Ver partidos</th>
			</tr>
			<tr>
				<td align="center">Fecha</td>
				<?php for($a=1;$a<20;$a++) { ?>
				<td align="center"><a href="tablaprode.php?fecha=<?php echo$a;?>&amp;jugador=<?php echo$_GET['jugador'];?>" title="Ver la fecha <?php echo$a;?>"><?php echo$a;?></a></td>
				<?php } ?>
			</tr>
		</table>
	</div>
	<div>
		<table cellspacing="0">
			<tr>
				<th colspan="3">Local</th>
				<th>&nbsp;</th>
				<th colspan="3">Visitante</th>
				<th>Resultado</th>
				<th>&nbsp;</th>
			</tr>
			<?php while ($partido = mysql_fetch_assoc($q_partidos)) { ?>
			<tr<?php if (!empty($partido['resultado'])) { ?> class="<?php if ($partido['resultado'] != $partido['prode']) { echo "in"; } ?>correcto"<?php } ?>>
				<td align="right"><input type="radio" name="partido[<?php echo $partido['id_partido'];?>]" value="L" id="L<?php echo $partido['id_partido'];?>" <?php if ($partido['prode'] == "L") { echo "checked "; } ?>disabled /></td>
				<td><img src="escudos/equipo<?php echo $partido['e1']; ?>.gif" alt="<?php echo $partido['local']; ?>" height="30" /></td>
				<td align="right"><?php echo $partido['local']; ?></td>
				<td align="center"><input type="radio" name="partido[<?php echo $partido['id_partido'];?>]" value="E" <?php if ($partido['prode'] == "E") { echo "checked "; } ?>disabled /></td>
				<td><?php echo $partido['visitante']; ?></td>
				<td><img src="escudos/equipo<?php echo $partido['e2']; ?>.gif" alt="<?php echo $partido['visitante']; ?>" height="30" /></td>
				<td><input type="radio" name="partido[<?php echo $partido['id_partido'];?>]" value="V" id="V<?php echo $partido['id_partido'];?>" <?php if ($partido['prode'] == "V") { echo "checked "; } ?>disabled /></td>
				<td align="center"><?php switch ($partido['resultado']) {
					case "L": echo "Local"; break;
					case "E": echo "Empate"; break;
					case "V": echo "Visitante"; break;
				};?></td>
				<td style="background-Color:#132884"><?php echo $partido['diashow']; ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<p>&nbsp;</p>
	<form action="tablaprode.php?jugador=<?php echo$_GET['jugador'];?>" method="post">
	<h2><a name="mensaje">Enviar un mensaje</a></h2>
	<p>Asunto: <input type="text" name="titulo" value="" size="40" /></p>
	<p>Mensaje:<br /><textarea name="mensaje" cols="40" rows="4"></textarea></p>
	<p><input type="submit" name="enviar" value="Enviar" class="button" /></p>
	</form>
<?php } ?>
</div>
</body>
</html>
