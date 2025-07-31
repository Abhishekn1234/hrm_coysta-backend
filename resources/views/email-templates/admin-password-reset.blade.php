<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ \App\CPU\translate('Password Reset') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: linear-gradient(135deg, #0052cc, #0041a8);
            padding: 30px 40px;
            color: #ffffff;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .body {
            padding: 30px 40px;
            color: #333333;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            background: #0052cc;
            color: white;
            text-decoration: none;
            padding: 14px 26px;
            font-size: 16px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #003f99;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #777777;
            background-color: #f4f6f8;
        }

        .footer a {
            color: #0052cc;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>{{ \App\CPU\translate('Reset Your Password') }}</h1>
    </div>
    <div class="body">
        <p>{{ \App\CPU\translate('Hi there,') }}</p>
        <p>{{ \App\CPU\translate('We received a request to reset your password. Click the button below to reset it.') }}</p>

        <a class="button" href="{{ $url }}">{{ \App\CPU\translate('Click to Reset') }}</a>

        <p style="margin-top: 30px;">{{ \App\CPU\translate("If you didn’t request a password reset, you can safely ignore this email.") }}</p>
        <p>{{ \App\CPU\translate('Thanks,') }}<br><strong>Cosysta Team</strong></p>
    </div>
    <div class="footer">
        {{ \App\CPU\translate("Need help?") }} <a href="mailto:support@cosysta.com">support@cosysta.com</a>
    </div>
</div>

</body>
</html>
