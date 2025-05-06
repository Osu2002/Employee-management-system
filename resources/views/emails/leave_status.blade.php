<!DOCTYPE html>
<html>
<head>
    <title>Leave Application Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #0056b3;
        }
        p {
            margin: 0.5em 0;
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
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Leave Application Status Update</h2>
        <p>Dear {{ $employeeName }},</p>

        <p>Your leave application has been updated. Here are the details:</p>

        <div class="details">
            <p><strong>Leave Type:</strong> {{ $leaveType }}</p>
            <p><strong>From Date:</strong> {{ $fromDate }}</p>
            <p><strong>To Date:</strong> {{ $toDate }}</p>
            <p><strong>Status:</strong> <span style="color: {{ $status === 'Approved' ? '#28a745' : ($status === 'Rejected' ? '#dc3545' : '#ffc107') }}">{{ $status }}</span></p>
            @if($instructions)
            <p><strong>Instructions:</strong> {{ $instructions }}</p>
            @else
            <p><strong>Instructions:</strong> None provided.</p>
            @endif
        </div>

        <p>If you have any questions, please do not hesitate to contact the HR department.</p>

        <p>Best regards,</p>
        <p><strong>HR Department</strong></p>

        <div class="footer">
            <p>This email was generated automatically. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
