# SISP php
This is implementation of a library to process
SISP [https://www.sisp.cv/](https://www.sisp.cv/)
vinti4 [https://www.vinti4.cv/](https://www.vinti4.cv/) payment in a easy way.

## Install
Download the project folder on your project.

## Include in your project
Import the library file;

```php
include "../Sisp.php";
```

## Create Transaction Object
Create the transaction object from the **Sisp** class.
You can pass three parameters:
- Your POS Id/Identifier
- The respective POS authentication code
- The VBV api URL, and it is set by default as "https://mc.vinti4net.cv/BizMPIOnUsSisp" 

```php
use Faxi\Sisp;

$payment = new Sisp("90000045", "kfyhhKJH875ndu44");
```

## Generate the HTML buy form
You can generate the HTML form
by calling the **buyForm** method.
It receives three parameters:
- The transaction Id, you will receive it in the transaction callback, it can be max of 15 characters
- The amount of the transaction
- The callback url, the transaction result will be sent to here

```php
$buyForm = $payment->buyForm("TR001", 1000, "http://localhost/sisp-php/src/Faxi/samples/callback-buy.php");
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