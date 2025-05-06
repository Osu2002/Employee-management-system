<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reply to HR Inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #0056b3;
        }
        .details {
            margin: 20px 0;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 4px;
        }
        .details p {
            margin: 0.3em 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Reply to HR Inquiry</h2>
        <p>Dear HR,</p>
        <p>You have received a new reply regarding the following inquiry:</p>

        <div class="details">
            <p><strong>Employee Name:</strong> {{ $mailDetails['employeeName'] }}</p>
            <p><strong>Subject:</strong> {{ $mailDetails['subject'] }}</p>
            <p><strong>Original Message:</strong> {{ $mailDetails['message'] }}</p>
        </div>

        <p><strong>Reply:</strong></p>
        <p>{{ $mailDetails['reply'] }}</p>

        <p>Please address this inquiry as soon as possible.</p>
        <p>Thank you!</p>
    </div>
</body>
</html>
