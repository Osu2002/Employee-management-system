<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New HR Inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #0056b3;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #0056b3;
            margin: 0;
        }
        .content p {
            line-height: 1.8;
            margin: 10px 0;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f1f7ff;
            border-left: 4px solid #0056b3;
            border-radius: 5px;
        }
        .details p {
            margin: 0.5em 0;
            font-size: 14px;
        }
        .details p strong {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #555;
        }
        .footer strong {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New HR Inquiry</h2>
        </div>
        <div class="content">
            <p>Hello HR Team,</p>
            <p>You have received a new inquiry from the <strong>{{ $name }}</strong>. Please find the details below:</p>
            <div class="details">
                <p><strong>Name:</strong> {{ $name }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Subject:</strong> {{ $subject }}</p>
                <p><strong>Message:</strong> {{ $messageContent }}</p>
            </div>
            <p>Please respond to this inquiry at your earliest convenience.</p>
        </div>
        <div class="footer">
            <p>Thank you,<br><strong>HR Management System</strong></p>
        </div>
    </div>
</body>
</html>
