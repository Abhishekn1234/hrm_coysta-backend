<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Message from Cosysta Technologies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: 'Source Sans Pro', sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #1a82e2;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 30px 20px;
            color: #333333;
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .email-footer {
            background-color: #f4f4f4;
            text-align: center;
            font-size: 13px;
            color: #888888;
            padding: 15px;
        }

        .email-footer a {
            color: #1a82e2;
            text-decoration: none;
        }

        @media screen and (max-width: 600px) {
            .email-body {
                padding: 20px 15px;
            }

            .email-header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <h1>Cosysta Technologies</h1>
                            <p style="margin: 5px 0 0; font-size: 14px;">Delivering Smart Solutions</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="email-body">
                            <p>Hi {{ $msg['name'] }},</p>
                            <div>{!! $msg['message'] !!}</div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            © {{ date('Y') }} Cosysta Technologies. All rights reserved.<br>
                            <a href="https://cosysta.com">www.cosysta.com</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
