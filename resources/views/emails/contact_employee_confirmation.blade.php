<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Received Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        h2 {
            color: #004085;
            margin-bottom: 15px;
        }
        p {
            margin: 0.5em 0;
            font-size: 15px;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f1f5fb;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }
        .details p {
            margin: 0.5em 0;
            font-size: 14px;
            color: #495057;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thank You for Reaching Out!</h2>
        <p>Dear {{ $messageDetails->name }},</p>
        <p>We have received your message and our team will get back to you shortly. Here are the details of your inquiry:</p>

        <div class="details">
            <p><strong>Name:</strong> {{ $messageDetails->name }}</p>
            <p><strong>Email:</strong> {{ $messageDetails->email }}</p>
            <p><strong>Subject:</strong> {{ $messageDetails->subject }}</p>
            <p><strong>Message:</strong> {{ $messageDetails->message }}</p>
        </div>

        <p>If you have any urgent questions, please feel free to contact us directly at <a href="mailto:hr@weblook.com">hr@weblook.com</a>.</p>
        <p>Best regards,</p>
        <p><strong>HR Department</strong></p>
        <div class="footer">
            &copy; {{ date('Y') }} Weblook International Private Limited. All rights reserved.
        </div>
    </div>
</body>
</html>
