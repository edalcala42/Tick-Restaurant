<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-product">
				<div class="card">
					<div class="card-header">
						   Formulario de producto
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Categoria</label>
								<select name="idCategoria" id="category_id" class="custom-select select2">
									<option value=""></option>
									<?php
									$qry = $conn->query("SELECT * FROM categoria order by nombre asc");
									while($row=$qry->fetch_assoc()):
										$cname[$row['id']] = ucwords($row['nombre']);
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['nombre'] ?></option>
								<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Nombre</label>
								<input type="text" class="form-control" name="nombre">
							</div>
							<div class="form-group">
								<label class="control-label">Descripcion</label>
								<textarea name="descripcion" id="description" cols="30" rows="4" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Precio</label>
								<input type="number" class="form-control text-right" name="precio">
							</div>
							<div class="form-group">
								<div class="custom-control custom-switch">
								  <input type="checkbox" class="custom-control-input" id="status" name="disponibilidad" checked value="1">
								  <label class="custom-control-label" for="status">Disponible</label>
								</div>
							</div>

                            <div>
                                <a href="imagenes.php">Añadair imagen - menu de imagenes</a>
                            </div>


					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-product').get(0).reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Lista de productos</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Categoria</th>
									<th class="text-center">Imagen</th>
									<th class="text-center">Informacion del producto</th>
									<th class="text-center">Accion</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$query = "SELECT * FROM tabla_imagen";
                                $resultado = $conn->query($query);
                                //while($row = $resultado->fetch_assoc())

								$product = $conn->query("SELECT * FROM producto order by id asc");
								while($row=$product->fetch_assoc()):
								$row2 = $resultado->fetch_assoc()
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p><b><?php echo $cname[$row['idCategoria']] ?></b></p>
									</td>
									<td><img height="70px" src="data:image/jpg;base64,<?php echo base64_encode($row2['imagen']); ?>"/></td>
									<td class="">
										<p>Nombre: <b><?php echo $row['nombre'] ?></b></p>
										<p><small>Precio: <b><?php echo number_format($row['precio'],2) ?></b></small></p>
										<p><small>Estatus: <b><?php echo $row['disponibilidad'] > 0 ? " Disponible" : "Agotado" ?></b></small></p>
										<p><small>Descripcion: <b><?php echo $row['descripcion'] ?></b></small></p>
										<p><small>ID: <b><?php echo $row['id'] ?></b></small></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_product" type="button" data-id="<?php echo $row['id'] ?>" data-description="<?php echo $row['descripcion'] ?>" data-name="<?php echo $row['nombre'] ?>"  data-price="<?php echo $row['precio'] ?>"  data-status="<?php echo $row['disponibilidad'] ?>" data-category_id="<?php echo $row['idCategoria'] ?>">Editar</button>
										<button class="btn btn-sm btn-danger delete_product" type="button" data-id="<?php echo $row['id'] ?>">Eliminar</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p {
		margin:unset;
	}
	.custom-switch{
		cursor: pointer;
	}
	.custom-switch *{
		cursor: pointer;
	}
</style>
<script>
	$('#manage-product').on('reset',function(){
		$('input:hidden').val('')
		$('.select2').val('').trigger('change')
	})
	
	$('#manage-product').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_product',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})
	$('.edit_product').click(function(){
		start_load()
		var cat = $('#manage-product')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='nombre']").val($(this).attr('data-name'))
		cat.find("[name='descripcion']").val($(this).attr('data-description'))
		cat.find("[name='precio']").val($(this).attr('data-price'))
		cat.find("[name='idCategoria']").val($(this).attr('data-category_id')).trigger('change')
		if($(this).attr('data-status') == 1)
			$('#status').prop('checked',true)
		else
			$('#status').prop('checked',false)
		end_load()
	})
	$('.delete_product').click(function(){
		_conf("Are you sure to delete this product?","delete_product",[$(this).attr('data-id')])
	})
	function delete_product($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_product',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>