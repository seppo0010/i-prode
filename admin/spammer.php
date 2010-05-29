<?php
include("../inc/conexion.php");
$q_mails = mysql_query("SELECT nombre, mail, ultimomail, recibirmail FROM prode_usuario") or die(mysql_error());
$q_fecha = mysql_query("SELECT min(fecha) as fecha FROM prode_partido WHERE to_days(dia) >= to_days(now()) - 1")	or die(mysql_error());
$fecha = mysql_fetch_assoc($q_fecha);
if ($fecha['fecha'] == 0 OR empty($fecha['fecha'])) {
	$fecha['fecha'] = 1;
}
$default = "<p>Finalizada la fecha ".$fecha['fecha']." del torneo, estos son los resultados y la tabla de posiciones</p><p>&nbsp;</p><p>[fecha]</p><p>[tabla]</p><p><a href=\"http://www.prode.neonetsi.com.ar\">www.prode.neonetsi.com.ar</a></p>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Spammer!</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="JavaScript" type="text/javascript" src="html2xhtml.js"></script>
	<script language="JavaScript" type="text/javascript" src="richtext.js"></script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0">
<tr> 
<td><div align="left">
<form name="RTEDemo" action="prueba.php" method="post" onsubmit="return submitForm();">
<p class="text">Remitente nombre <br />
<input type="text" name="remitente" value="Prode">
</p>
<p class="text">Remitente mail <br />
    <input type="text" name="remitentemail" value="seppo0010@gmail.com" size="40">
</p>
<p class="text">Fecha <br />
<input type="text" name="fecha" value="<?php echo $fecha['fecha'] ?>">
</p>
<p class="text">Destino<br />
<?php
while ($mailes = mysql_fetch_assoc($q_mails)) { ?>
	<input type="checkbox" name="destinos[]" value="<?php echo $mailes['mail']; ?>"<?php if ($mailes['recibirmail'] == "S") { ?> checked<?php } ?>>&nbsp;<?php echo $mailes['nombre'] ." (".$mailes['mail']."). Ultimo mail: ".$mailes['ultimomail']."<br />";
}
?>
<br /><br /><br />
<span style="font-size:10px ">Ingresar mails, separados por &quot;,&quot;<br />Ej: &quot;seppo@xasamail.com,info@consultoresbm.com&quot;</span><br />
<textarea name="To" cols="60" rows="2">
</textarea></p>
<p class="text">Asunto <br />
<input type="text" name="Asunto" value="Prode - Fecha <?php echo $fecha['fecha'] ?>"></p>
<p class="text">Texto <br />
<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
	//make sure hidden and iframe values are in sync before submitting form
	//to sync only 1 rte, use updateRTE(rte)
	//to sync all rtes, use updateRTEs
	updateRTE('rte1');
	//updateRTEs();
	
	//change the following line to true to submit form
	return true;
}

//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML)
initRTE("images/", "", "", true);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>

<script language="JavaScript" type="text/javascript">
<!--
writeRichText('rte1', '<?php echo $default; ?>', 600, 300, true, false);
//-->
</script>
<p><input type="submit" name="submit" value="Enviar"></p>
</form>
</div></td>
</tr>
</table>

</body>
</html>
