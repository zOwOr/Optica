<?php

	/*-------------------------
	Autor: WEB OPERATIONS - Roberto Garcia
	Web: http://www.weboperations.mx
	Mail: rg@weboperations.mx
	---------------------------*/
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

   

	if (isset($_GET['id'])){
		$numero_nota_venta=intval($_GET['id']);
		$del1="delete from nota_venta where numero_nota_venta='".$numero_nota_venta."'";
		$del2="delete from detalle_nota_venta where numero_nota_venta='".$numero_nota_venta."'";
		if ($delete1=mysqli_query($con,$del1) and $delete2=mysqli_query($con,$del2)){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong> Datos eliminados exitosamente
			</div>
			<?php 
		}else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> No se puedo eliminar los datos
			</div>
			<?php
			
		}
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		  $sTable = "nota_venta, clientes, users";
		 $sWhere = "";
		 $sWhere.=" WHERE nota_venta.id_cliente=clientes.id_cliente and nota_venta.id_repartidor=users.user_id  ";
		if ( $_GET['q'] != "" )
		{
		$sWhere.= " and  (clientes.nombre_cliente like '%$q%' or nota_venta.numero_nota_venta like '%$q%')";
			
		}
		
		$sWhere.=" order by nota_venta.id_nota_venta desc  ";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './nota-venta.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
		
        
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			echo mysqli_error($con);
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="info">
					<th>#</th>
					<th>Fecha</th>
					<th>Cliente</th>
					<th>Repartidor</th>
					<th>Estado</th>
					<th class='text-right'>Total</th>
					<th class='text-right'>Acciones</th>
					
				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
						$id_nota_venta=$row['id_nota_venta'];
						$numero_nota_venta=$row['numero_nota_venta'];
						$nombre_cliente=$row['nombre_cliente'];		
						$fecha=date("d/m/Y", strtotime($row['fecha_nota_venta']));
						$telefono_cliente=$row['telefono_cliente'];
						$email_cliente=$row['email_cliente'];
						$nombre_vendedor=$row['firstname']." ".$row['lastname'];
						$nombre_repartidor=$row['firstname']." ".$row['lastname'];
					
						$estado_nota_venta=$row['estado_nota_venta'];
						if ($estado_nota_venta==1){$text_estado="Pagada";$label_class='label-success';}
						if ($estado_nota_venta==2){$text_estado="Pendiente";$label_class='label-warning';}
						if ($estado_nota_venta==3){$text_estado="cancelada";$label_class='label-danger';}

						$total_venta=$row['total_venta'];
					?>
					<tr>
						<td><?php echo $numero_nota_venta; ?></td>
						<td><?php echo $fecha; ?></td>
						<td><a href="#" data-toggle="tooltip" data-placement="top" title="<i class='glyphicon glyphicon-phone'></i> <?php echo $telefono_cliente;?><br><i class='glyphicon glyphicon-envelope'></i>  <?php echo $email_cliente;?>" ><?php echo $nombre_cliente;?></a></td>
						<td><?php echo $nombre_repartidor; ?></td>
						<td><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
						<td class='text-right'><?php echo number_format ($total_venta,2); ?></td>					
					<td class="text-right">
						<a href="editar_notaventa.php?id_nota_venta=<?php echo $id_nota_venta;?>" class='btn btn-default' title='Editar Nota de Venta' ><i class="glyphicon glyphicon-edit"></i></a> 
						<a href="#" class='btn btn-default' title='Descargar Nota de Venta' onclick="imprimir_nota('<?php echo $id_nota_venta;?>');"><i class="glyphicon glyphicon-download"></i></a> 
					<!-- <a href="#" class='btn btn-default' title='Borrar Nota de Venta' onclick="eliminar_nota('')"><i class="glyphicon glyphicon-trash"></i> </a> -->
					</td>
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=7><span class="pull-right"><?php
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>