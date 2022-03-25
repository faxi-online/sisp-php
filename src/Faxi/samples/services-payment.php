<?php

include "../Sisp.php";

use Faxi\Sisp;

$payment = new Sisp("90000045", "kfyhhKJH875ndu44");

$buyForm = $payment->servicePaymentForm("TR001", 1000, "123456789", "6", "http://localhost/sisp-php/src/Faxi/samples/callback-buy.php");

?>

<html>
	<head>
		<title>Services Payment Transaction</title>
	</head>
	<body>

		<div>

			<h5>Services Payment Transaction</h5>

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