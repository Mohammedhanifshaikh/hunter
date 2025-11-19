<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thank You for Contacting Us</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Thank You for Contacting Us, {{ $data['name'] }}</h1>
        <p>We have received your message with the following details:</p>
        <ul>
            <li><strong>Name:</strong> {{ $data['name'] }}</li>
            <li><strong>Email:</strong> {{ $data['email'] }}</li>
            <li><strong>Subject:</strong> {{ $data['subject'] }}</li>
            <li><strong>Message:</strong> {{ $data['message'] }}</li>
            <li><strong>Terms and Conditions:</strong> {{ $data['termsAndConditions'] }}</li>
        </ul>
        <p>We will get back to you as soon as possible.</p>
    </div>
</body>

</html>
