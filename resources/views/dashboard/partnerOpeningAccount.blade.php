<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title> Welcome to Evan Global Management </title>
    <style>
        /* Inline styles for simplicity, consider using CSS classes for larger templates */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #222222;
            color: #fff;
        }

        .header {
            max-width: 560px;
            padding: 20px;
            background-color: #000;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h3> Welcome to Evan Global Management!, </h3>
        </div>

        <div style="padding: 20px; width: 560px">
            <span style="color: #fff;"> Dear {{$mailData['first_name'] . " " . $mailData['last_name']}} </span>,
            <p> 
                Welcome to our Evan Global Managemnt platform. Your password is <b> {{$mailData['password']}} </b>. 
                You need to login first use this password and please update your password in your account.
            </p>
            <br>

            <p style="color: #fff;"> Thank You (Global Management Team) </p>
            <span style="color: #fff!important;"> info@evanglobalmanagement.com </span><br>
            <span> <a style="color: #fff;" href="https://evanglobalmanagement.com" target="_blank"> evanglobalmanagement.com </a> </span>
        </div>
    </div>
</body>
</html>