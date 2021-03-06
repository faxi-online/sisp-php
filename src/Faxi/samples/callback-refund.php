<?php

include "../Sisp.php";

use Faxi\Sisp;

$payment = new Sisp("90000045", "kfyhhKJH875ndu44");

$payment->onRefundResult(

		// success callback
		function ($transaction_id){

			echo "<p>Refunded done for $transaction_id</p>";

		},

		// error callback
		function ($transaction_id, $errorDescription, $errorDetail, $errorAdditionalMessage){

			echo "<p>Error on refund for $transaction_id</p>";
			echo "<p>Error: description $errorDescription</p>";
			echo "<p>Error: detail $errorDetail</p>";
			echo "<p>Error: additional $errorAdditionalMessage</p>";

		},

		// cancellation callback
		function (){

			echo "<p>Refund cancelled</p>";

		}

	);

?>

