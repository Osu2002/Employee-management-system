<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS OTP Verification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color:#D33333;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .content h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .otp {
            font-size: 20px;
            font-weight: bold;
            color: #c80101;
        }
        .footer {
            background-color: #f4f4f4;
            color: #777777;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>EMS OTP Verification</h2>
        </div>
        <div class="content">
            <h1>Hello, {{ $employee_name }}</h1>
            <p>Your OTP for login is:</p>
            <p class="otp">{{ $otp }}</p>
            <p>Please enter this OTP to proceed with your login.</p>
            <p>Thank you!</p>
        </div>
        <div class="footer">
            <p>If you did not request this OTP, please contact our support team immediately.</p>
            <p>&copy; {{ date('Y') }} Weblook International Pvt Ltd.. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
