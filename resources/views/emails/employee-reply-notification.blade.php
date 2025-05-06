<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Your HR Inquiry</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
            line-height: 1.8;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 25px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e3e3e3;
        }

        h2 {
            color: #0056b3;
            margin-bottom: 20px;
            font-size: 1.6rem;
            text-align: center;
        }

        .details {
            background-color: #f9fafc;
            border: 1px solid #d1d7dc;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }

        .details p {
            margin: 0.5em 0;
            font-size: 1rem;
        }

        p {
            margin: 0.5em 0;
            font-size: 1rem;
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
            font-size: 0.9rem;
            color: #555;
            text-align: center;
            border-top: 1px solid #e3e3e3;
            padding-top: 10px;
        }

        .footer a {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Reply to Your HR Inquiry</h2>
        <p>Dear <strong>{{ $mailDetails['employeeName'] }}</strong>,</p>

        <p>Thank you for reaching out to us. We have reviewed your inquiry regarding
            <strong>{{ $mailDetails['subject'] }}</strong>. Please find our response below:</p>

        <div class="details">
            <p><strong>Your Message:</strong> {{ $mailDetails['originalMessage'] }}</p>
            <p><strong>Our Reply:</strong> {{ $mailDetails['adminReply'] }}</p>
        </div>

        <p>If you need further assistance, feel free to contact us at <a href="mailto:hr@weblook.com">hr@weblook.com</a>.
        </p>
        <p>We appreciate your patience and understanding.</p>

        <p>Best regards,</p>
        <p><strong>HR Department</strong></p>

        <div class="footer">
            <p>© {{ date('Y') }} Weblook International Private Limited</p>
            <p><a href="mailto:hr@weblook.com">hr@weblook.com</a> | <a href="https://weblook.com">weblook.com</a></p>
        </div>
    </div>
</body>

</html>
