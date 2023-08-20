<?php
	/*-------------------------
	Autor: WEB OPERATIONS - Roberto Garcia
	Web: http://www.weboperations.mx
	Mail: rg@weboperations.mx
	---------------------------*/
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
$id_nota_venta= $_SESSION['id_nota_venta'];
$numero_nota_venta= $_SESSION['numero_nota_venta'];
if (isset($_POST['id'])){$id=intval($_POST['id']);}
if (isset($_POST['cantidad'])){$cantidad=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){$precio_venta=floatval($_POST['precio_venta']);}

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	//Archivo de funciones PHP
	include("../funciones.php");
if (!empty($id) and !empty($cantidad) and !empty($precio_venta))
{
$insert_tmp=mysqli_query($con, "INSERT INTO detalle_nota_venta (numero_nota_venta, id_producto,cantidad,precio_venta) VALUES ('$numero_nota_venta','$id','$cantidad','$precio_venta')");

}
if (isset($_GET['id']))//codigo elimina un elemento del array
{
$id_detalle=intval($_GET['id']);	
$delete=mysqli_query($con, "DELETE FROM detalle_nota_venta WHERE id_detalle='".$id_detalle."'");
}
$simbolo_moneda=get_row('perfil','moneda', 'id_perfil', 1);
?>
<table class="table">
<tr>
	<th class='text-center'>CODIGO</th>
	<th class='text-center'>CANT.</th>
	<th>DESCRIPCION</th>
	<th class='text-right'>PRECIO UNIT.</th>
	<th class='text-right'>PRECIO TOTAL</th>
	<th></th>
</tr>
<?php
	$sumador_total=0;
	$sql=mysqli_query($con, "select * from products, nota_venta, detalle_nota_venta where nota_venta.numero_nota_venta=detalle_nota_venta.numero_nota_venta and  nota_venta.id_nota_venta='$id_nota_venta' and products.id_producto=detalle_nota_venta.id_producto");
	while ($row=mysqli_fetch_array($sql))
	{
	$id_detalle=$row["id_detalle"];
	$codigo_producto=$row['codigo_producto'];
	$cantidad=$row['cantidad'];
	$nombre_producto=$row['nombre_producto'];
	
	
	$precio_venta=$row['precio_venta'];
	$precio_venta_f=number_format($precio_venta,2);//Formateo variables
	$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
	$precio_total=$precio_venta_r*$cantidad;
	$precio_total_f=number_format($precio_total,2);//Precio total formateado
	$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
	$sumador_total+=$precio_total_r;//Sumador
	
		?>
		<tr>
			<td class='text-center'><?php echo $codigo_producto;?></td>
			<td class='text-center'><?php echo $cantidad;?></td>
			<td><?php echo $nombre_producto;?></td>
			<td class='text-right'><?php echo $precio_venta_f;?></td>
			<td class='text-right'><?php echo $precio_total_f;?></td>
			<td class='text-center'><a href="#" onclick="eliminar('<?php echo $id_detalle ?>')"><i class="glyphicon glyphicon-trash"></i></a></td>
		</tr>		
		<?php
	}
	$impuesto=get_row('perfil','impuesto', 'id_perfil', 1);
	$subtotal=number_format($sumador_total,2,'.','');
	$total_iva=($subtotal * $impuesto )/100;
	$total_iva=number_format($total_iva,2,'.','');
	$total_nota_venta=$subtotal+$total_iva;
	$update=mysqli_query($con,"update nota_venta set total_venta='$total_nota_venta' where id_nota_venta='$id_nota_venta'");
?>
<tr>
	<td class='text-right' colspan=4>SUBTOTAL <?php echo $simbolo_moneda;?></td>
	<td class='text-right'><?php echo number_format($subtotal,2);?></td>
	<td></td>
</tr>
<tr>
	<td class='text-right' colspan=4>IVA (<?php echo $impuesto;?>)% <?php echo $simbolo_moneda;?></td>
	<td class='text-right'><?php echo number_format($total_iva,2);?></td>
	<td></td>
</tr>
<tr>
	<td class='text-right' colspan=4>TOTAL <?php echo $simbolo_moneda;?></td>
	<td class='text-right'><?php echo number_format($total_nota_venta,2);?></td>
	<td></td>
</tr>

</table>
