<?php

include "../Sisp.php";

use Faxi\Sisp;

$payment = new Sisp("90000045", "kfyhhKJH875ndu44");

$payment->onTransactionResult(

        // success callback
        function ($transaction_id, $clearingPeriod, $sisp_transaction_id){

            echo "<p>Payment sucessfully for $transaction_id</p>";

            echo "<p>merchantRespCP: " . $clearingPeriod. "</p>";
            echo "<p>merchantRespTid: " . $sisp_transaction_id . "</p>";

        },

        // error callback
        function ($transaction_id, $errorDescription, $errorDetail, $errorAdditionalMessage){

            echo "<p>Error on transaction $transaction_id</p>";
            echo "<p>Error: description $errorDescription</p>";
            echo "<p>Error: detail $errorDetail</p>";
            echo "<p>Error: additional $errorAdditionalMessage</p>";

        },

        // cancellation callback
        function (){

            echo "<p>Transaction cancelled</p>";

        }

    );

?>

