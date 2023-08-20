<?php
	/*-------------------------
	Autor: WEB OPERATIONS - Roberto Garcia
	Web: http://www.weboperations.mx
	Mail: rg@weboperations.mx
	---------------------------*/
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }

	$active_reportes = "";
	$active_proveedor = "";
	$active_facturas="";
	$active_productos = "";
	$active_clientes = "";
	$active_usuarios = "";
    $active_notasVenta = "active";
    $active_proveedor = "";
    $active_acercade = "";
    $active_almacen = "";
    $active_perfil="";
    $active_stock ="";
    $active_categoria="";
	$title="Inicio | Sistema de Facturación";

	$title="Editar Factura | Sistema de Facturación";
	
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	if (isset($_GET['id_nota_venta']))
	{
		$id_nota_venta=intval($_GET['id_nota_venta']);
		$campos="clientes.id_cliente, clientes.nombre_cliente, clientes.telefono_cliente, clientes.email_cliente, nota_venta.id_vendedor, nota_venta.fecha_nota_venta, nota_venta.condiciones, nota_venta.estado_nota_venta, nota_venta.numero_nota_venta";
		$sql_nota_venta=mysqli_query($con,"select $campos from nota_venta, clientes where nota_venta.id_cliente=clientes.id_cliente and id_nota_venta='".$id_nota_venta."'");
		$count=mysqli_num_rows($sql_nota_venta);
		if ($count==1)
		{
				$rw_nota_venta=mysqli_fetch_array($sql_nota_venta);
				$id_cliente=$rw_nota_venta['id_cliente'];
				$nombre_cliente=$rw_nota_venta['nombre_cliente'];
				$telefono_cliente=$rw_nota_venta['telefono_cliente'];
				$email_cliente=$rw_nota_venta['email_cliente'];
				$id_vendedor_db=$rw_nota_venta['id_vendedor'];
				$fecha_nota_venta=date("d/m/Y", strtotime($rw_nota_venta['fecha_nota_venta']));
				$condiciones=$rw_nota_venta['condiciones'];
				$estado_nota_venta=$rw_nota_venta['estado_nota_venta'];
				$numero_nota_venta=$rw_nota_venta['numero_nota_venta'];
				$_SESSION['id_nota_venta']=$id_nota_venta;
				$_SESSION['numero_nota_venta']=$numero_nota_venta;
		}	
		else
		{
			header("location: nota-venta.php");
			exit;	
		}
	} 
	else 
	{
		header("location: nota-venta.php");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
  </head>
  <body>
	<?php
	include("navbar.php");
	?>  
    <div class="container">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h4><i class='glyphicon glyphicon-edit'></i> Editar Nota de Venta</h4>
		</div>
		<div class="panel-body">
		<?php 
			include("modal/buscar_productos.php");
			include("modal/registro_clientes.php");
			include("modal/registro_productos.php");
		?>
			<form class="form-horizontal" role="form" id="datos_nota_venta">
				<div class="form-group row">
				  <label for="nombre_cliente" class="col-md-1 control-label">Cliente</label>
				  <div class="col-md-3">
					  <input type="text" class="form-control input-sm" id="nombre_cliente" placeholder="Selecciona un cliente" required value="<?php echo $nombre_cliente;?>">
					  <input id="id_cliente" name="id_cliente" type='hidden' value="<?php echo $id_cliente;?>">	
				  </div>
				  <label for="tel1" class="col-md-1 control-label">Teléfono</label>
							<div class="col-md-2">
								<input type="text" class="form-control input-sm" id="tel1" placeholder="Teléfono" value="<?php echo $telefono_cliente;?>" readonly>
							</div>
					<label for="mail" class="col-md-1 control-label">Email</label>
							<div class="col-md-3">
								<input type="text" class="form-control input-sm" id="mail" placeholder="Email" readonly value="<?php echo $email_cliente;?>">
							</div>
				 </div>
						<div class="form-group row">
							<label for="empresa" class="col-md-1 control-label">Vendedor</label>
							<div class="col-md-3">
								<select class="form-control input-sm" id="id_vendedor" name="id_vendedor" disabled>
									<?php
										$sql_vendedor=mysqli_query($con,"select * from users order by lastname");
										while ($rw=mysqli_fetch_array($sql_vendedor)){
											$id_vendedor=$rw["user_id"];
											$nombre_vendedor=$rw["firstname"]." ".$rw["lastname"];
											if ($id_vendedor==$id_vendedor_db){
												$selected="selected";
											} else {
												$selected="";
											}
											?>
											<option value="<?php echo $id_vendedor?>" <?php echo $selected;?>><?php echo $nombre_vendedor?></option>
											<?php
										}
									?>
								</select>
							</div>
							
							<label for="tel2" class="col-md-1 control-label">Fecha</label>
							<div class="col-md-2">
								<input type="text" class="form-control input-sm" id="fecha" value="<?php echo $fecha_nota_venta;?>" readonly>
							</div>
							<label for="email" class="col-md-1 control-label">Pago</label>
							<div class="col-md-2">
								<select class='form-control input-sm ' id="condiciones" name="condiciones">
								
									<option value="1" <?php if ($condiciones==1){echo "selected";}?>>Crédito</option>
								</select>
							</div>
							<div class="col-md-2">
								<select class='form-control input-sm ' id="estado_nota_venta" name="estado_nota_venta">
									<option value="1" <?php if ($estado_nota_venta==1){echo "selected";}?>>Pagado</option>
									<option value="2" <?php if ($estado_nota_venta==2){echo "selected";}?>>Pendiente</option>
									<option value="3" <?php if ($estado_nota_venta==3){echo "selected";}?>>Cancelado</option>
								</select>
							</div>
						</div>
				<div class="from-group row"></div>
				<label for="empresa" class="col-md-1 control-label">Repartidor</label>
							<div class="col-md-3">
								<select class="form-control input-sm" id="id_repartidor" name="id_repartidor">
									<?php
										$sql_vendedor=mysqli_query($con,"select * from users where Tipo='cobra' order by lastname");
										while ($rw=mysqli_fetch_array($sql_vendedor)){
											$id_vendedor=$rw["user_id"];
											$nombre_vendedor=$rw["firstname"]." ".$rw["lastname"];
											if ($id_vendedor==$id_vendedor_db){
												$selected="selected";
											} else {
												$selected="";
											}
											?>
											<option value="<?php echo $id_vendedor?>" <?php echo $selected;?>><?php echo $nombre_vendedor?></option>
											<?php
										}
									?>
								</select>
							</div>
				
				<div class="col-md-12">
					<div class="pull-right">
						<button type="submit" class="btn btn-default">
						  <span class="glyphicon glyphicon-refresh"></span> Actualizar datos
						</button>
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#nuevoProducto">
						 <span class="glyphicon glyphicon-plus"></span> Nuevo producto
						</button>
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#nuevoCliente">
						 <span class="glyphicon glyphicon-user"></span> Nuevo cliente
						</button>
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
						 <span class="glyphicon glyphicon-search"></span> Agregar productos
						</button>
						<button type="button" class="btn btn-default" onclick="imprimir_nota_venta('<?php echo $id_nota_venta;?>')">
						  <span class="glyphicon glyphicon-print"></span> Imprimir
						</button>
					</div>	
				</div>
			</form>	
			<div class="clearfix"></div>
				<div class="editar_nota_venta" class='col-md-12' style="margin-top:10px"></div><!-- Carga los datos ajax -->	
			
		<div id="resultados" class='col-md-12' style="margin-top:10px"></div><!-- Carga los datos ajax -->			
			
		</div>
	</div>		
		 
	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="js/editar_notaventa.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
		$(function() {
						$("#nombre_cliente").autocomplete({
							source: "./ajax/autocomplete/clientes.php",
							minLength: 2,
							select: function(event, ui) {
								event.preventDefault();
								$('#id_cliente').val(ui.item.id_cliente);
								$('#nombre_cliente').val(ui.item.nombre_cliente);
								$('#tel1').val(ui.item.telefono_cliente);
								$('#mail').val(ui.item.email_cliente);
																
								
							 }
						});
						 
						
					});
					
	$("#nombre_cliente" ).on( "keydown", function( event ) {
						if (event.keyCode== $.ui.keyCode.LEFT || event.keyCode== $.ui.keyCode.RIGHT || event.keyCode== $.ui.keyCode.UP || event.keyCode== $.ui.keyCode.DOWN || event.keyCode== $.ui.keyCode.DELETE || event.keyCode== $.ui.keyCode.BACKSPACE )
						{
							$("#id_cliente" ).val("");
							$("#tel1" ).val("");
							$("#mail" ).val("");
											
						}
						if (event.keyCode==$.ui.keyCode.DELETE){
							$("#nombre_cliente" ).val("");
							$("#id_cliente" ).val("");
							$("#tel1" ).val("");
							$("#mail" ).val("");
						}
			});	
	</script>

  </body>
</html>