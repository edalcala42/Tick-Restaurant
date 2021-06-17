<?php include('db_connect.php');?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo isset($_SESSION['system']['name']) ? $_SESSION['system']['name'] : '' ?></title>
 	

<?php
 include('./header.php'); 
 // include('./auth.php'); 
 ?>

</head>
<style>
	input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.3); /* IE */
  -moz-transform: scale(1.3); /* FF */
  -webkit-transform: scale(1.3); /* Safari and Chrome */
  -o-transform: scale(1.3); /* Opera */
  transform: scale(1.3);
  padding: 10px;
  cursor:pointer;
}
</style>
<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Pedido</b>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Fecha</th>
									<th class="">No.Referencia</th>
									<th class="">ID Mesa</th>
									<th class="">Cargo</th>
									<!--<th class="">Estatus</th>-->
									<th class="">IVA</th>
									<th class="">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$order = $conn->query("SELECT * FROM factura WHERE id_mesa = '1245' and cantidadPagada=0 order by unix_timestamp(fecha) desc ");
								while($row=$order->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<p> <b><?php echo date("M d,Y",strtotime($row['fecha'])) ?></b></p>
									</td>
									<td>
										<p> <b><?php echo $row['cantidadPagada'] > 0 ? $row['NumRefer'] : 'N/A' ?></b></p>
									</td>
									<td>
										<p> <b><?php echo $row['id_mesa'] ?></b></p>
									</td>
									<td>
										<p class="text-right"> <b><?php echo number_format($row['pago'],2) ?></b></p>
									</td>
									<!--<td class="text-center">
										<?php if($row['cantidadPagada'] > 0): ?>
											<span class="badge badge-success">Paid</span>
										<?php else: ?>
											<span class="badge badge-primary">Unpaid</span>
										<?php endif; ?>
									</td>-->
									<!--<td>
										<p class="text-right"> <b><?php echo number_format($row['total_con_iva'],2) ?></b></p>
									</td>
									<td>
										<p class="text-right"> <b><?php echo number_format($row['iva'],2) ?></b></p>
									</td>-->
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
	<div class="row justify-content-center">
		<button class="btn btn-success" onclick="location.href='http://localhost/Tick-Restaurant/pedidocompletado.php'">Pagar Pedido</button>
		<button class="btn btn-danger" onclick="location.href='http://localhost/Tick-Restaurant/menu.php'">Regresar</button>
	</div>
</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	$('#new_order').click(function(){
		uni_modal("New order ","manage_order.php","mid-large")
		
	})
	$('.view_order').click(function(){
		uni_modal("Order  Details","view_order.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_order').click(function(){
		_conf("Are you sure to delete this order ?","delete_order",[$(this).attr('data-id')])
	})
	function delete_order($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_order',
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
</script>