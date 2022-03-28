<?php

include "../Sisp.php";

use Faxi\Sisp;

$payment = new Sisp("90000045", "kfyhhKJH875ndu44");

$transaction_id = "T" . date('YmdHms');
$buyForm = $payment->phoneRechargeForm(
		$transaction_id,
		1000,
		9112233,
		2,
		"http://localhost/sisp-php/src/Faxi/samples/callback-buy.php"
	);

?>

<html>
	<head>
		<title>Phone Recharge Transaction</title>
	</head>
	<body>

		<div>

			<h5>Phone Recharge Transaction</h5>

			<?= $buyForm ?>
            
            <button onclick="startTransaction()">
                Start Transaction
            </button>

		</div>

		<script>
			
			function startTransaction()
			{
				document.forms[0].submit();
			}

		</script>
	</body>
</html>