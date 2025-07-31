<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ \App\CPU\translate('Password Reset') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @media screen {
            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 400;
                src: url('https://fonts.gstatic.com/s/sourcesanspro/v14/6xK3dSBYKcSV-LCoeQqfX1RYOo3qNa7lujcbP6lLDFw.woff2') format('woff2');
            }
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: 'Source Sans Pro', sans-serif;
        }

        .wrapper {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: linear-gradient(135deg, #0052cc, #0041a8);
            padding: 30px;
            color: #ffffff;
            text-align: center;
        }

        .header h1 {
            font-size: 22px;
            margin: 0;
        }

        .content {
            padding: 30px 40px;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }

        .content p {
            margin-bottom: 20px;
        }

        .footer {
            padding: 20px 40px;
            text-align: center;
            font-size: 12px;
            color: #888;
            background-color: #f9fafb;
        }

        a.button {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 20px;
            background-color: #0052cc;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background-color: #003f99;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="header">
        <h1>{{ \App\CPU\translate('Notification') }}</h1>
    </div>
    <div class="content">
        {!! $body !!}
    </div>
    <div class="footer">
        {{ \App\CPU\translate("If you didn’t request this, you can ignore this message.") }}<br>
        &copy; {{ date('Y') }} {{ env('APP_NAME', 'YourCompany') }}
    </div>
</div>

</body>
</html>
