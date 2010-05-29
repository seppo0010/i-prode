<div align="center" id="menu">
	<img src="../escudos/logo_arg.gif" width="53" height="60" alt="Logo de la AFA" style="margin-bottom:8px; " />
<?php if (!empty($_SESSION['admin'])) { ?>
	<ul>
		<li><a href="fecha.php" accesskey="1" title="Ver los &uacute;ltimos partidos disputados e indicar sus resultados (alt + 1)">&Uacute;ltimos partidos</a></li>
		<li><a href="admin.php" accesskey="2" title="Ver y editar los datos de los administradores (alt + 2)">Administradores</a></li>
		<li><a href="usuarios.php" accesskey="3" title="Ver y editar los datos de los usuarios (alt + 3)">Usuarios</a></li>
		<li><a href="noticia.php" accesskey="4" title="Ver y editar las noticias del Prode (alt + 4)">Noticias</a></li>
		<li><a href="encuesta.php" accesskey="5" title="Ver y editar las encuestas del Prode (alt + 5)">Encuestas</a></li>
		<li><a href="backup_db.php" title="Descargar copia de seguridad de la base de datos">Backup</a></li>
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
	</div>
	</form>
<?php } ?>
</div>