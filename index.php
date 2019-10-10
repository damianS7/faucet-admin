<?php 
@session_start();

if(!isset($_SESSION["logged"]))
	header('Location: login.php');

require 'balancebox-lib.php';

if(isset($_POST["send"]))
	$send_response = coinbase_send($_POST["to"], $_POST["amount"], $_POST["faucet"]);
?>

<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="UTF-8"> 
	<meta name="viewport" content="width=device-width">
	<title>BalanceBox - Home</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<style type="text/css">
		*{ font-family: Ubuntu; }
		form div {margin: 5px 0}
	</style>
</head>
<body class="bg-info">
	<p>&nbsp;</p>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-md-2 col-md-push-1">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Balance</h3>
					</div>
					<div class="panel-body">
						<?php echo @coinbase_get_balance(); ?>
					</div>
				</div>

				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Last transactions</h3>
					</div>
					<div class="panel-body">
						<?php 
						$transactions = @coinbase_get_transactions();

						if(is_array($transactions)) {
							foreach ($transactions as $t) {
								if(!empty($t->getDescription())) {

									echo "<p><span class=\"label label-danger\">{$t->getAmount()->getAmount()} BTC</span> sent to: {$t->getDescription()} at: {$t->getCreatedAt()->format('Y-m-d H:i:s')} <span class=\"label label-info\">{$t->getStatus()}</span></p>";
								}
							}	
						}

						?>
					</div>
				</div>
				<div class="">
					<a href="./logout.php"> <button type="button" class="btn btn-block">Logout</button></a>	
				</div>
				
			</div>

			<div class="col-xs-12 col-md-8 col-md-push-1">
				
				<table class="table table-striped table-hover" style="background-color:#FFF">
					<thead class="bg-primary">
						<tr>
							<th>Faucet</th>
							<th>Currency</th>
							<th>Balance</th>
							<th></th>
						</tr>	
					</thead>
					<tbody>
						<?php foreach (get_config()["faucets"] as $k => $f): ?>
						
								<tr data-faucet="<?php echo $k; ?>" data-currency="<?php echo $f["currency"]; ?>">
									<td>
										<?php echo $k; ?>
										<?php if($f["auto"]): ?>
											<span class="label label-success">AutoRecharge +<?php echo $f["reload_amount"]; ?> <?php echo $f["currency"]; ?></span>
											
										<?php endif; ?>
									</td>
									<td><?php echo $f["currency"]; ?></td>
									<td class="balance"></td>
									<td>
										<button onClick="send_form('<?php echo $k; ?>', '<?php echo $f["currency"]; ?>', '<?php echo $f["to"]; ?>', '<?php echo $f["reload_amount"];  ?>');" type="button" class="btn-primary btn btn-xs" data-toggle="modal" data-target="#send-modal">Send</button>
										<span class="label label-info"><?php echo $f["to"]; ?></span>
									</td>
								</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div id="send-modal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Send </h4>
				</div>

				<form class="form-horizontal" method="POST">
					<fieldset>
						<div class="form-group modal-body">
							<label class="col-lg-2 control-label">Faucet </label>
							<div class="col-lg-10">
								<input class="form-control" type="text" readonly="" name="faucet" id="faucet"></input>
							</div>

							<label class="col-lg-2 control-label">Currency </label>
							<div class="col-lg-10">
								<input class="form-control" type="text" readonly="" name="currency" id="currency"</input>
							</div>

							<label class="col-lg-2 control-label">To </label>
							<div class="col-lg-10">
								<input class="form-control" type="text" readonly="" name="to" id="to"></input>
							</div>

							<label class="col-lg-2 control-label">Amount </label>
							<div class="col-lg-10">
								<input class="form-control" type="text" name="amount" id="amount"></input>
							</div>							
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" name="send" class="btn btn-primary">Send</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		(function($){
			$(document).ready(function() {
				update_balance();
				setInterval(function(){
					update_balance();
				}, 60000);
			});
		})(jQuery);

		function update_balance() {
			$('table > tbody > tr').each(function() {
				ax($(this).find('.balance'), $(this).attr('data-faucet'), $(this).attr('data-currency'));
			});
		}

		function send_form(faucet, currency, to, amount) {
			$('#send-modal #to').val(to);
			$('#send-modal #amount').val(amount);
			$('#send-modal #faucet').val(faucet);
			$('#send-modal #currency').val(currency);
		}

		function ax(e, f, c) {			
			$.post("balancebox-lib.php",
		    { action: "fbb", faucet: f, currency: c },
		    function(data, status){;
		        $(e).html(data);
		    });
		}
	</script>
</body>
</html>