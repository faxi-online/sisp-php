# SISP php
This is implementation of a library to process
SISP [https://www.sisp.cv/](https://www.sisp.cv/)
vinti4 [https://www.vinti4.cv/](https://www.vinti4.cv/) payment in a easy way.

## Install
Download the project folder on your project.
Or install it using composer:
```
composer require faxi-online/sisp-php:dev-main
```

## Include in your project
Import the library file;

```php
include "../Sisp.php";
```

Or include the composer autoload
```php
include "vendor/autoload.php";
```

## Create Transaction Object
Create the transaction object from the **Sisp** class.
You can pass three parameters:
- Your POS Id/Identifier
- The respective POS authentication code
- The VBV api URL, and it is set by default as "https://mc.vinti4net.cv/BizMPIOnUsSisp",
remember to define it value in production, without the path "/CardPayment" because
it will be added automatically according the transaction code

```php
use Faxi\Sisp;

$payment = new Sisp(
        "90000045",
        "kfyhhKJH875ndu44"
    );

```

## Generate Transaction id
Generate your transaction id, it can be max of 15 characters,
after a successful payment you should not reuse that id for new transaction.
```php
// sample to generate id from timestamp
$transaction_id = "T" . date('YmdHms');
```

## Generate the HTML buy form
You can generate the HTML form
by calling the **buyForm** method.
It receives three parameters:
- The transaction Id, you will receive it in the transaction callback
- The amount of the transaction
- The callback url, the transaction result will be sent to here

```php
$buyForm = $payment->buyForm(
		$transaction_id,
		1000,
		"http://localhost/sisp-php/src/Faxi/samples/callback-buy.php"
	);
```

## Put the form on your HTML page
Just put that form in your HTML page
and submit it by calling **document.forms[0].submit();**

```html
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
```

After submitted the form, you should be redirect to a page like the below.
![Payment form](/docs/payment-form.png)

## Transaction result callback
To process callback result we should use the method **onTransactionResult**,
it receive three parameters:
- The success callback function
- The error callback function
- The cancellation callback function

```php
$payment = new Sisp(
        "90000045",
        "kfyhhKJH875ndu44"
    );

$payment->onTransactionResult(

    // success callback
    function ($transaction_id){

        echo "<p>Payment sucessfully for $transaction_id</p>";

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
```
## Generate phone recharge HTML form
You can generate the HTML form
by calling the **phoneRechargeForm** method.
It receives five parameters:
- The transaction Id, you will receive it in the transaction callback, it can be max of 15 characters
- The amount of the transaction
- The phone number you want to recharge
- The operator id (it will be provided by SISP)
- The callback url, the transaction result will be sent to here

```php
$buyForm = $payment->phoneRechargeForm(
		$transaction_id,
		1000,
		9112233,
		2,
		"http://localhost/sisp-php/src/Faxi/samples/callback-buy.php"
	);
```

## Generate service payment HTML form
You can generate the HTML form
by calling the **servicePaymentForm** method.
It receives five parameters:
- The transaction Id, you will receive it in the transaction callback, it can be max of 15 characters
- The amount of the transaction
- The reference number of the bill you want to pay
- The enity id (it will be provided by SISP)
- The callback url, the transaction result will be sent to here

```php
$buyForm = $payment->servicePaymentForm(
		$transaction_id,
		1000,
		"123456789",
		"6",
		"http://localhost/sisp-php/src/Faxi/samples/callback-buy.php"
	);
```

## Internationalization
If you want you can change the language of payment form presented to user,
it supports en and pt.

```php
$payment->lang = "pt";
```