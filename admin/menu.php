<?php if (!empty($_SESSION['id'])) {
$q_cant_correo = mysql_query("SELECT COUNT(id_correo) AS cant FROM prode_correo WHERE receptor = '".$_SESSION['id']."' AND leido = 'N'"); 
$cant_correo = mysql_fetch_assoc($q_cant_correo);
} ?><div align="center" id="menu">
	<img src="escudos/logo_arg.gif" width="53" height="60" alt="Logo de la AFA" style="margin-bottom:8px; " />
<?php if (!empty($_SESSION['id'])) { ?>
	<ul>
		<li><a href="noticias.php" accesskey="0" title="Ver Las &uacute;ltimas novedades (alt + 0)">Noticias</a></li>
		<li><a href="fecha.php" accesskey="1" title="Ver y modificar los pron&oacute;sticos de la pr&oacute;xima semana (alt + 1)">Pr&oacute;ximos partidos</a></li>
		<li><a href="tabla.php" accesskey="2" title="Ver la tabla de posiciones del torneo actual (alt + 2)">Posiciones torneo</a></li>
		<li><a href="tablaprode.php" accesskey="3" title="Ver la tabla de posiciones del Prode (alt + 3)">Posiciones Prode</a></li>
		<li><a href="correo.php" accesskey="4" title="Ir a la bandeja de entrada (alt + 4)">Correo<?php if ($cant_correo['cant'] > 0) { echo "<span style=\"color:red\"> (".$cant_correo['cant'].")</span>"; } ?></a></li>
		<li><a href="micuenta.php" accesskey="5" title="Editar mi informaci&oacute;n personal (alt + 5)">Mi cuenta</a></li>
		<li><a href="yqsi.php" accesskey="6" title="Qué hubiese pasado si... (alt + 6)">Y que si...</a></li>
		<li><a href="estadisticas.php" accesskey="7" title="Ver las estad&iacute;sticas del torneo y del prode (alt + 7)">Estad&iacute;sticas</a></li>
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
	<div align="center" style="width:80%;"><a href="index.php?opcion=registrarse" title="Si no posee una cuenta, haga click aqu&iacute; para crear una">Registrarse</a></div>
	</div>
	</form>
<?php } ?>
</div>
