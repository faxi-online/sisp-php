<?php

include "../Sisp.php";

use Faxi\Sisp;

$payment = new Sisp("90000045", "kfyhhKJH875ndu44");

$buyForm = $payment->buyForm("TR001", 1000, "http://localhost/sisp-php/src/Faxi/samples/callback-buy.php");

//echo($buyForm);

?>

<html>
	<head>
		<title>Do payment</title>
	</head>
	<body>

		<div>

			<h5>Do payment</h5>

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