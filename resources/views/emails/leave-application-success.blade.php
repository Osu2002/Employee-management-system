<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Application Success</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f4f4;
            color: #444;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 15px;
            background: linear-gradient(90deg, #e53935, #e78383);
            color: white;
            font-size: 22px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .content {
            padding: 20px 30px;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .content strong {
            color: #333;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #e53935;
            border-radius: 5px;
        }
        .details p {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 15px 0;
            background-color: #ffffff;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #af4c4c;
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Leave Application Successfully Submitted
        </div>
        <div class="content">
            <p>Dear <strong>{{ $leaveDetails['employee_name'] }}</strong>,</p>
            <p>Your leave application has been successfully submitted. Below are the details of your application:</p>

            <div class="details">
                <p><strong>Leave Type:</strong> {{ $leaveDetails['leave_type'] }}</p>
                <p><strong>From Date:</strong> {{ $leaveDetails['from_date'] }}</p>
                <p><strong>To Date:</strong> {{ $leaveDetails['to_date'] }}</p>
                <p><strong>Duration:</strong> {{ $leaveDetails['duration'] }}</p>
                <p><strong>Comments:</strong> {{ $leaveDetails['comments'] ?? 'N/A' }}</p>
            </div>

            <p>We will notify you once your leave application is reviewed. If you have any questions or require further assistance, please do not hesitate to contact the HR department.</p>
        </div>
        <div class="footer">
            <p>Thank you for using our system.</p>
            <p>&copy; {{ date('Y') }} <strong>Weblook International Private Limited</strong></p>
        </div>
    </div>
</body>
</html>
