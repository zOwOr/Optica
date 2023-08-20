<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	$id_nota_venta= $_SESSION['id_nota_venta'];
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['id_cliente'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['id_repartidor'])) {
           $errors[] = "Selecciona el repartidor";
        } else if (empty($_POST['condiciones'])){
			$errors[] = "Selecciona forma de pago";
		} else if ($_POST['estado_nota_venta']==""){
			$errors[] = "Selecciona el estado de la nota de venta";
		} else if (
			!empty($_POST['id_cliente']) &&
			!empty($_POST['id_repartidor']) &&
			!empty($_POST['condiciones']) &&
			$_POST['estado_nota_venta']!="" 
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$id_cliente=intval($_POST['id_cliente']);
		$id_repartidor=intval($_POST['id_repartidor']);
		$condiciones=intval($_POST['condiciones']);

		$estado_nota_venta=intval($_POST['estado_nota_venta']);
		
		$sql="UPDATE nota_venta SET id_cliente='".$id_cliente."', id_repartidor='".$id_repartidor."', condiciones='".$condiciones."', estado_nota_venta='".$estado_nota_venta."' WHERE id_nota_venta='".$id_nota_venta."'";
		$query_update = mysqli_query($con,$sql);
			if ($query_update){
				$messages[] = "La nota de venta ha sido actualizada satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
			}
		} else {
			$errors []= "Error desconocido.";
		}
		
		if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}

?>