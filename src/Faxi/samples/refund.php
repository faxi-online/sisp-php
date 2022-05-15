<?php

include "../Sisp.php";

use Faxi\Sisp;

$payment = new Sisp("90000045", "kfyhhKJH875ndu44");

// generate your transaction id
// it can be max of 15 characters
// after a successful payment you should not reuse that id for new transaction
$transaction_id = "T" . date('YmdHms');

$refundForm = $payment->refundForm(
		$transaction_id,
		1000,
		1763,
		228,
		"http://localhost/sisp-php/src/Faxi/samples/callback-buy.php"
	);

//echo($refundForm);

?>

<html>
	<head>
		<title>Refund</title>
	</head>
	<body>

		<div>

			<h5>Refund</h5>

			<?= $refundForm ?>

            <button onclick="startTransaction()">
                Start
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