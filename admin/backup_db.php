<?php

@session_start();

if (empty($_SESSION['id'])) { header("location:index.php"); exit; }



/* Usuario para la conexion a Mysql. */

$usurio = "neoweb";

/* Password para la conexion a Mysql. */

$passwd = "webweb";

/* Host para la conexion a Mysql. */

$host = "200.68.94.57";

/* Base de Datos que se seleccionará. */

$bd = "demositio";

/* Nombre del fichero que se descargará. */

$nombre = "backup prode ".date('Y-m-d').".sql";

/* Determina si la tabla será vaciada (si existe) cuando  restauremos la tabla. */            

$drop = false;

/*

* Array que contiene las tablas de la base de datos que seran resguardadas.

* Puede especificarse un valor false para resguardar todas las tablas

* de la base de datos especificada en  $bd.

*

* Ejs.:

* $tablas = false;

*    o

* $tablas = array("tabla1", "tabla2", "tablaetc");

*

*/

$tablas = false;

/*

* Tipo de compresion.

* Puede ser "gz", "bz2", o false (sin comprimir)

*/

$compresion = false;



/* Conexion y eso*/

$conexion = mysql_connect($host, $usurio, $passwd)

or die("No se conectar con el servidor MySQL: ".mysql_error());

mysql_select_db($bd, $conexion)

or die("No se pudo seleccionar la Base de Datos: ". mysql_error());





/* Se busca las tablas en la base de datos */

if ( empty($tablas) ) {

	$consulta = "SHOW TABLES FROM $bd;";

	$respuesta = mysql_query($consulta, $conexion)

	or die("No se pudo ejecutar la consulta: ".mysql_error());

	while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {

		if (substr($fila[0],0,5) == "prode") {

			$tablas[] = $fila[0];

		}

	}

}





/* Se crea la cabecera del archivo */

$info['dumpversion'] = "1.1b";

$info['fecha'] = date("d-m-Y");

$info['hora'] = date("h:m:s A");

$info['mysqlver'] = mysql_get_server_info();

$info['phpver'] = phpversion();

ob_start();

print_r($tablas);

$representacion = ob_get_contents();

ob_end_clean ();

preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $matches);

$info['tablas'] = implode(";  ", $matches[1]);

$dump = <<<EOT

# +===================================================================

# | YoDumpeo! {$info['dumpversion']}

# | por fran86 <fran86@myrealbox.com>

# |

# | Generado el {$info['fecha']} a las {$info['hora']} por el usurio '$usurio'

# | Servidor: {$_SERVER['HTTP_HOST']}

# | MySQL Version: {$info['mysqlver']}

# | PHP Version: {$info['phpver']}

# | Base de datos: '$bd'

# | Tablas: {$info['tablas']}

# |

# +-------------------------------------------------------------------



EOT;

foreach ($tablas as $tabla) {

	

	$drop_table_query = "";

	$create_table_query = "";

	$insert_into_query = "";

	

	/* Se halla el query que será capaz vaciar la tabla. */

	if ($drop) {

		$drop_table_query = "DROP TABLE IF EXISTS `$tabla`;";

	} else {

		$drop_table_query = "# No especificado.";

	}



	/* Se halla el query que será capaz de recrear la estructura de la tabla. */

	$create_table_query = "";

	$consulta = "SHOW CREATE TABLE $tabla;";

	$respuesta = mysql_query($consulta, $conexion)

	or die("No se pudo ejecutar la consulta: ".mysql_error());

	while ($fila = mysql_fetch_array($respuesta, MYSQL_NUM)) {

			$create_table_query = $fila[1].";";

	}

	

	/* Se halla el query que será capaz de insertar los datos. */

	$insert_into_query = "";

	$consulta = "SELECT * FROM $tabla;";

	$respuesta = mysql_query($consulta, $conexion)

	or die("No se pudo ejecutar la consulta: ".mysql_error());

	while ($fila = mysql_fetch_array($respuesta, MYSQL_ASSOC)) {

			$columnas = array_keys($fila);

			foreach ($columnas as $columna) {

				if ( gettype($fila[$columna]) == "NULL" ) {

					$values[] = "NULL";

				} else {

					$values[] = "'".$fila[$columna]."'";

				}

			}

			$insert_into_query .= "INSERT INTO `$tabla` VALUES (".implode(", ", $values).");\n";

			unset($values);

	}

	

$dump .= <<<EOT



# | Vaciado de tabla '$tabla'

# +------------------------------------->

$drop_table_query





# | Estructura de la tabla '$tabla'

# +------------------------------------->

$create_table_query





# | Carga de datos de la tabla '$tabla'

# +------------------------------------->

$insert_into_query



EOT;

}



/* Envio */

if ( !headers_sent() ) {

	header("Pragma: no-cache");

	header("Expires: 0");

	header("Content-Transfer-Encoding: binary");

	switch ($compresion) {

	case "gz":

		header("Content-Disposition: attachment; filename=$nombre.gz");

		header("Content-type: application/x-gzip");

		echo gzencode($dump, 9);

		break;

	case "bz2":

		header("Content-Disposition: attachment; filename=$nombre.bz2");

		header("Content-type: application/x-bzip2");

		echo bzcompress($dump, 9);

		break;

	default:

		if ($_GET['opcion'] == "mail") {

				$file = "backup prode ".date('Y-m-d').".sql";

				$fp = fopen($file,"w+");

				fwrite($fp,$dump);

				fclose($fp);

				if (!class_exists("phpmailer")) {

					require("mailer/class.phpmailer.php");

				}

				$mail = new PHPMailer();

//				$mail->SetLanguage("es");

				$mail->IsSMTP(); // telling the class to use SMTP

				$mail->Host = "localhost"; // SMTP server

				$mail->From = "seppo@xasamail.com";

				$mail->FromName = "Prode";

				$mail->AddAttachment($file);

				$mail->Subject = $file;

				$mail->IsHTML(true);

				$mail->Body = "";

				$mail->AddAddress("seppo0010@yahoo.com.ar");

				$mail->Send();

				echo $mail->ErrorInfo;

				unlink($file);

			} else {

			    header("Content-Disposition: attachment; filename=\"".$nombre."\"");

				header("Content-type: application/force-download");

				echo $dump;

		}

	}

} else {

	echo "<b>ATENCION: Probablemente ha ocurrido un error</b><br />\n<pre>\n$dump\n</pre>";

}

 