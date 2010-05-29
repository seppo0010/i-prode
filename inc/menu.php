<?php if (!empty($_SESSION['id'])) {
$q_cant_correo = mysql_query("SELECT COUNT(id_correo) AS cant FROM prode_correo WHERE receptor = '".$_SESSION['id']."' AND leido = 'N'"); 
$cant_correo = mysql_fetch_assoc($q_cant_correo);
$q_cant_encuesta = mysql_query("SELECT SUM(IF(id_encuesta IS NOT NULL,1,0)) - SUM(IF(id_voto IS NOT NULL,1,0)) AS remanente FROM prode2_encuesta e LEFT JOIN prode2_voto v ON e.id_encuesta = v.encuesta AND usuario = '".$_SESSION['id']."'");
$cant_encuesta = mysql_fetch_assoc($q_cant_encuesta);
} ?><div align="center" id="menu">
	<p><img src="escudos/logo_arg.gif" width="53" height="60" alt="Logo de la AFA" style="margin-bottom:8px; " /></p>
<?php if (!empty($_SESSION['id'])) { ?>
	<ul>
		<li><a href="noticias.php" accesskey="N" title="Ver Las &uacute;ltimas novedades (alt + N)">Noticias</a></li>
		<li><a href="encuesta.php" accesskey="A" title="Participa de las encuestas para ayudarnos a mejorar el sitio (alt + A)"><?php if ($cant_encuesta['remanente'] > 0) { echo "<span style=\"color:#6F6;font-weight:bold;\">"; } ?>Encuestas<?php if ($cant_encuesta['remanente'] > 0) { echo "</span>"; } ?></a></li>
		<li><a href="fecha.php" accesskey="P" title="Ver y modificar los pron&oacute;sticos de la pr&oacute;xima semana (alt + P)">Pr&oacute;ximos partidos</a></li>
		<li><a href="tabla.php" accesskey="T" title="Ver la tabla de posiciones del torneo actual (alt + T)">Posiciones torneo</a></li>
		<li><a href="tablaprode.php" accesskey="O" title="Ver la tabla de posiciones del Prode (alt + O)">Posiciones Prode</a></li>		<li><a href="mensaje.php" accesskey="M" title="Ver y publicar mensajes (alt + M)">Mensajes</a></li>
		<li><a href="correo.php" accesskey="C" title="Ir a la bandeja de entrada (alt + C)">Correo<?php if ($cant_correo['cant'] > 0) { echo "<span style=\"color:red\"> (".$cant_correo['cant'].")</span>"; } ?></a></li>
		<li><a href="micuenta.php" accesskey="U" title="Editar mi informaci&oacute;n personal (alt + U)">Mi cuenta</a></li>
		<li><a href="yqsi.php" accesskey="Y" title="Qué hubiese pasado si... (alt + Y)">Y que si...</a></li>
		<li><a href="estadisticas.php" accesskey="E" title="Ver las estad&iacute;sticas del torneo y del prode (alt + E)">Estad&iacute;sticas</a></li>
				<li><a href="sugerencia.php" accesskey="S" title="Sugerir cambios en la p&aacute;gina (alt + S)">Sugerencia</a></li>
		<li><a href="index.php?accion=salir" accesskey="Q" title="Cerrar la sesi&oacute;n actual (alt + Q)">Salir</a></li>
	</ul>
<?php } else { ?>
	<form action="index.php" method="post">
	<div align="left">
	<p><label for="usuario">Nombre de usuario</label></p>
	<p>&nbsp;<input type="text" name="usuario" value="" id="usuario" /></p>
	<p><label for="clave">Contrase&ntilde;a</label></p>
	<p>&nbsp;<input type="password" name="clave" value="" id="clave" /></p>
	<p>&nbsp;<input type="submit" name="enviar" value="Entrar" /></p>
	<div align="center" style="width:80%;margin-bottom:12px;"><a href="index.php?opcion=registrarse" title="Si no posee una cuenta, haga click aqu&iacute; para crear una">Registrarse</a></div>
	</div>
	</form>
<?php } ?>
</div>
