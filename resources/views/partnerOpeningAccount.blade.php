

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title> Partner Account Opening </title>
    <style>
        /* Inline styles for simplicity, consider using CSS classes for larger templates */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 16px;
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
            <h3> {{$emailContent['title'] }} </h3>
            <p style="color: #fff;"> Dear {{$mailData['first_name'] . " " . $mailData['last_name']}} </p>

           <h1> 
                <span> Your password is </span>
                <code> {{ $mailData['password']}} </code>
           </h1>
        </div>

        <div style="padding: 20px; width: 560px">
            {{$emailContent['content'] }}
        </div>
    </div>
</body>
</html>