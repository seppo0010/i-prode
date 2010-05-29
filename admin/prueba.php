<?php
function rteSafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = $strText;
	
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
//	$tmpString = str_replace("\"", "\"", $tmpString);
	
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);
	
	return $tmpString;
}

$_POST['rte1'] = stripslashes(rteSafe($_POST['rte1']));

include("../inc/conexion.php");
if ($_POST['fecha'] > 0 AND $_POST['fecha'] <= 19) { } else { $_POST['fecha'] = 1; }

$posiciones = "<table cellspacing=\"2\" cellpadding=\"2\" bgcolor=\"#92c1d0\" border=\"1\" color:\"#ffffff\"><tbody>".chr(13);
$posiciones .= "<tr><td valign=\"middle\" width=\"20\">Nº</td><td valign=\"middle\" width=\"80\">Usuario</td><td valign=\"middle\" width=\"20\">Pts</td><td valign=\"middle\" width=\"25\">Promedio</td></tr>".chr(13);
$q_posicion = mysql_query("SELECT id_usuario, u.usuario, COUNT(pro.id_pronostico) AS puntos, ROUND(100 * COUNT(pro.id_pronostico) / COUNT(pr.id_pronostico),2) AS promedio FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico AND pr.resultado = pa.resultado LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.resultado IS NOT NULL AND pa.resultado != '' AND pr.resultado IS NOT NULL AND pr.resultado != '' GROUP BY usuario, id_usuario ORDER BY puntos DESC LIMIT 0, 10");
//$q_posicion = mysql_query("SELECT id_usuario, u.usuario, COUNT(pro.id_pronostico) AS puntos, ROUND(100 * COUNT(pro.id_pronostico) / COUNT(pr.id_pronostico),2) AS promedio FROM prode2_pronostico pr LEFT JOIN prode_partido pa ON pr.partido = pa.id_partido LEFT JOIN prode2_pronostico pro ON pr.id_pronostico = pro.id_pronostico AND pr.resultado = pa.resultado LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE pa.resultado IS NOT NULL AND pa.resultado != '' AND pr.resultado IS NOT NULL GROUP BY usuario, id_usuario ORDER BY puntos DESC LIMIT 0, 10");
for ($a=1;$a<=mysql_num_rows($q_posicion);$a++) {
	$posicion = mysql_fetch_assoc($q_posicion);
	$posiciones .= "<tr>
	<td valign=\"middle\" align=\"center\">".$a. "</td>"
	."<td align=\"left\"><font onmouseover=\"this.style.color = \'blue\'; this.style.textDecorationUnderline = true;\" onmouseout=\"this.style.color = \'\'; this.style.textDecorationUnderline = false;\">".$posicion['usuario']."</font></td>"
	."<td valign=\"middle\" align=\"center\">".$posicion['puntos']."</td>"
	."<td valign=\"middle\" align=\"center\">".$posicion['promedio']."%</td>"
	."</tr>".chr(13);
}
$posiciones .= "</tbody></table>";

ob_start();
require("mailer/class.phpmailer.php");
$mail = new PHPMailer();
$mail->SetLanguage("es");
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = "localhost"; // SMTP server
$mail->From = $_POST['remitentemail'];
$mail->FromName = $_POST['remitente'];
$mail->AddEmbeddedImage("cruz.jpg", "cruz", "cruz.jpg");
$mail->AddEmbeddedImage("star1.jpg", "star1", "star1.jpg");
$mail->AddEmbeddedImage("star.jpg", "star", "star.jpg");
$mail->Subject = $_POST['Asunto'];
$mail->IsHTML(true);

foreach ($_POST['destinos'] as $destino) {
	if ($_GET['id'] > 0 AND $_GET['id'] <= 19) { } else { $_GET['id'] = 1; }
	$q_partido = mysql_query("SELECT e1.largo as local, e2.largo as visitante, p.resultado, pr.resultado AS pronostico FROM prode_partido p LEFT JOIN prode_equipo e1 ON p.local = e1.id_equipo LEFT JOIN prode_equipo e2 ON p.visitante = e2.id_equipo LEFT JOIN prode2_pronostico pr ON p.id_partido = pr.partido LEFT JOIN prode_usuario u ON pr.usuario = u.id_usuario WHERE u.mail = '".$destino."' AND p.fecha = ".$_POST['fecha']." ORDER BY id_partido ASC") or die(mysql_error());
	$estafecha = "<table cellspacing=\"2\" cellpadding=\"2\" bgcolor=\"#ffffff\" border=\"1\" color=\"#ffffff\"><tbody>".chr(13);
	while ($partido = mysql_fetch_assoc($q_partido)) {
		$estafecha .= "<tr><td width=\"15\">";
		if ($partido['resultado'] == "L" AND $partido['pronostico'] != "L") { $estafecha .=  "<img src=\"cid:star\">";}
		elseif ($partido['resultado'] == "L" AND $partido['pronostico'] == "L") { $estafecha .=  "<img src=\"cid:star1\">";}
		elseif ($partido['resultado'] != "L" AND $partido['pronostico'] == "L") { $estafecha .=  "<img src=\"cid:cruz\">";}
		$estafecha .= "</td><td bgcolor=\"#92c1d0\">".$partido['local']."</td><td width=\"15\">";
		if ($partido['resultado'] == "E" AND $partido['pronostico'] != "E") { $estafecha .=  "<img src=\"cid:star\">";}
		elseif ($partido['resultado'] == "E" AND $partido['pronostico'] == "E") { $estafecha .=  "<img src=\"cid:star1\">";}
		elseif ($partido['resultado'] != "E" AND $partido['pronostico'] == "E") { $estafecha .=  "<img src=\"cid:cruz\">";}
		$estafecha .= "</td><td bgcolor=\"#92c1d0\">".$partido['visitante']."</td><td width=\"15\">";
		if ($partido['resultado'] == "V" AND $partido['pronostico'] != "V") { $estafecha .=  "<img src=\"cid:star\">";}
		elseif ($partido['resultado'] == "V" AND $partido['pronostico'] == "V") { $estafecha .=  "<img src=\"cid:star1\">";}
		elseif ($partido['resultado'] != "V" AND $partido['pronostico'] == "V") { $estafecha .=  "<img src=\"cid:cruz\">";}
		$estafecha .= "</td></tr>".chr(13);
	}
	$estafecha .= "</tbody></table>";

	$referencia = "<table cellspacing=\"2\" cellpadding=\"2\" bgcolor=\"#ffffff\" border=\"1\" color=\"#ffffff\"><tbody>".chr(13);
	$referencia .= "<tr><td width=\"15\"><img src=\"cid:star1\">";
	$referencia .= "</td><td bgcolor=\"#92c1d0\">Acierto</td>";
	$referencia .= "</tr>".chr(13);
	$referencia .= "<tr><td width=\"15\"><img src=\"cid:star\">";
	$referencia .= "</td><td bgcolor=\"#92c1d0\">Respuesta correcta</td>";
	$referencia .= "</tr>".chr(13);
	$referencia .= "<tr><td width=\"15\"><img src=\"cid:cruz\">";
	$referencia .= "</td><td bgcolor=\"#92c1d0\">Respoesta incorrecta seleccionada</td>";
	$referencia .= "</tr>".chr(13);
	$referencia .= "</tbody></table>";

	mysql_free_result($q_partido);
	
	$msg = $_POST['rte1'];
	$msg = str_replace("[tabla]",$posiciones,$msg);
	$msg = str_replace("[fecha]",$estafecha,$msg);
	$msg = str_replace("[ref]",$referencia,$msg);
	unset($estafecha);

	$mail->Body = $msg;
	$mail->AddAddress($destino);
	if($mail->Send()) {
		mysql_query("UPDATE prode_usuario SET ultimomail = now() WHERE mail = '".$destino."'") or die(mysql_error());
	} else {
		echo $mail->ErrorInfo."<br />";
	}
	$mail->ClearAddresses();
}
$mostrar = ob_get_contents();
ob_end_clean();
if (empty($mostrar)) { 
	echo "Mails enviados correctamente";
} else {
	echo $mostrar;
}
?>