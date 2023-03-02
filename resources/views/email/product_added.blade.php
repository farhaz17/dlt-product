<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New product added!</title>
</head>
<body>
    <p>Hello {{ $userName }},</p>

    <p>A new product has been added:</p>

    <ul>
        <li><strong>Name:</strong> {{ $productName }}</li>
        <li><strong>Price:</strong> {{ $productPrice }}</li>
        <li><strong>Status:</strong> {{ $productStatus }}</li>
        <li><strong>Type:</strong> {{ $productType }}</li>
    </ul>

    <p>Thank you for using our application!</p>
</body>
</html>
