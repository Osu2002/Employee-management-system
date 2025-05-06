<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reply from Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fc;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 25px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 1px solid #ddd;
        }
        h2 {
            color: #007bff;
            margin-bottom: 15px;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .details {
            background-color: #f0f4f8;
            border: 1px solid #d1d7dc;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .details p {
            margin: 0.4em 0;
        }
        p {
            margin: 0.5em 0;
            font-size: 1rem;
            color: #555;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Reply from {{ $mailDetails['employeeName'] }}</h2>
        <p>Dear HR,</p>

        <p>
            You have received a new reply from
            <strong>{{ $mailDetails['employeeName'] }}</strong>
            (<a href="mailto:{{ $mailDetails['employeeEmail'] }}">{{ $mailDetails['employeeEmail'] }}</a>)
            regarding the subject <strong>{{ $mailDetails['subject'] }}</strong>.
            Please find the details below:
        </p>

        <div class="details">
            <p><strong>Original Message:</strong></p>
            <p>{{ $mailDetails['originalMessage'] }}</p>

            <p><strong>Reply:</strong></p>
            <p>{{ $mailDetails['replyMessage'] }}</p>
        </div>

        <p>Please log in to the HR system for further actions.</p>

        <p>Best regards,</p>
        <p><strong>HR System</strong></p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Weblook International Private Limited. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
