<?php

include "../Sisp.php";

use Faxi\Sisp;

$payment = new Sisp("90000045", "kfyhhKJH875ndu44");

// generate your transaction id
// it can be max of 15 characters
// after a successful payment you should not reuse that id for new transaction
$transaction_id = "T" . date('YmdHms');

$buyForm = $payment->buyForm(
		$transaction_id,
		1000,
		"http://localhost/sisp-php/src/Faxi/samples/callback-buy.php"
	);

//echo($buyForm);

?>

<html>
	<head>
		<title>Buy Transaction</title>
	</head>
	<body>

		<div>

			<h5>Buy Transaction</h5>

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