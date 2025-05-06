<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Approved</title>
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
            background-color: #5cb85c;
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
            color: #5cb85c;
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            Project Approved!
        </div>
        <div class="email-body">
            <h1>Dear {{ $employeeName }},</h1>
            <p>We are pleased to inform you that your project <strong>{{ $projectName }}</strong> has been approved by HR.</p>
            <p>Thank you for your contribution, and we look forward to seeing more great work from you!</p>
        </div>
        <div class="email-footer">
            &copy; 2025 Weblook International Pvt Ltd. All rights reserved.
        </div>
    </div>
</body>
</html>
