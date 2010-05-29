<?php
include("inc/conexion.php");
if (count($_POST) > 0) {
	mysql_query("INSERT INTO prode_partido (fecha, partido, local, visitante) VALUES ('".$_POST['fecha']."', '".$_POST['partido']."', '".$_POST['local']."', '".$_POST['visitante']."')") or die(mysql_error());
	
}

$q_equipo = mysql_query("SELECT * FROM prode_equipo ORDER BY largo ASC");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html>
<head>
	<title>Insertar prode</title>
</head>
<body onload="document.getElementById('local').focus();">
<form action="a.php" method="post">
<input type="text" name="partido" value="<?php $_POST['partido'] += 1; if ($_POST['partido'] == 10) { $_POST['partido'] = 0; $_POST['fecha'] += 1; } echo $_POST['partido']; ?>" /><br />
<input type="text" name="fecha" value="<?php echo $_POST['fecha'] ; ?>" /><br />
<select name="local" id="local">
	<?php while ($e = mysql_fetch_assoc($q_equipo)) { ?>
		<option value="<?php echo $e['id_equipo']; ?>"><?php echo $e['largo']; ?></option>
	<?php } ?>
</select><br />
<select name="visitante">
	<?php mysql_data_seek($q_equipo,0);while ($e = mysql_fetch_assoc($q_equipo)) { ?>
		<option value="<?php echo $e['id_equipo']; ?>"><?php echo $e['largo']; ?></option>
	<?php } ?>
</select><br />
<input type="submit" />

</form>

</body>
</html>