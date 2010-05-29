<?php if (!empty($_SESSION['admin']) OR !empty($_SESSION['id'])) { ?>
<div id="fechas">
	<table border="0">
		<tr>
			<th colspan="20" align="center">Temporada <?php echo date("Y"); ?></th>
		</tr>
		<tr>
			<td align="center">Fecha</td>
			<?php for($a=1;$a<20;$a++) { ?>
			<td align="center"><a href="fecha.php?fecha=<?php echo$a;?>" title="Ver la fecha <?php echo$a;?>"><?php echo$a;?></a></td>
			<?php } ?>
		</tr>
	</table>
</div>
<?php } ?>
