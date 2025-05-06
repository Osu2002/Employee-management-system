<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Project is Awaiting Approval</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #d9534f;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .email-body {
            padding: 20px;
            line-height: 1.6;
        }

        .email-body h1 {
            font-size: 1.5rem;
            color: #d9534f;
            margin-bottom: 10px;
        }

        .email-body p {
            margin: 10px 0;
            font-size: 1rem;
        }

        .email-footer {
            text-align: center;
            padding: 15px;
            background-color: #f5f5f5;
            font-size: 0.9rem;
            color: #777;
        }

        .email-footer a {
            color: #d9534f;
            text-decoration: none;
            font-weight: bold;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            Your Project is Awaiting Approval
        </div>
        <div class="email-body">
            <h1>Dear {{ $employeeName }},</h1>
            <p>Your project <strong>{{ $projectName }}</strong> has been successfully submitted and is now awaiting
                approval.</p>
            <p>We will notify you as soon as your project is reviewed by the team.</p>
            <p>Thank you for your efforts and contributions!</p>
        </div>
        <div class="email-footer">
            &copy; 2025 Weblook International Pvt Ltd. All rights reserved. <br>
            Need help? <a href="mailto:hr@weblook.com">Contact Support</a>
        </div>
    </div>
</body>

</html>
