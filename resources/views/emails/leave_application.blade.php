<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Leave Application Submitted</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #e53935;
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .content h3 {
            color: #333;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .content p strong {
            color: #555;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }
        .footer a {
            color: #e53935;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #e53935;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .btn:hover {
            background-color: #d32f2f;
        }
        .proof-status {
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            background-color: #ff7043;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>New Leave Application Submitted</h2>
        </div>

        <div class="content">
            <h3>Dear HR,</h3>
            <p>A new leave application has been submitted by the employee. Please find the details below:</p>

            <p><strong>Employee Name:</strong> {{ $leaveDetails['employee_name'] }}</p>
            <p><strong>Leave Type:</strong> {{ $leaveDetails['leave_type'] }}</p>
            <p><strong>From Date:</strong> {{ $leaveDetails['from_date'] }}</p>
            <p><strong>To Date:</strong> {{ $leaveDetails['to_date'] }}</p>
            <p><strong>Duration:</strong> {{ $leaveDetails['duration'] }}</p>
            <p><strong>Comments:</strong> {{ $leaveDetails['comments'] ?? 'N/A' }}</p>

            <p><strong>Proof:</strong>
                @if($leaveDetails['proof'])
                    <span class="proof-status">Attached</span>
                @else
                    <span class="proof-status">Not Provided</span>
                @endif
            </p>

            {{-- <a href="{{ url('/leaves?leave_id=' . $leaveDetails['leave_id']) }}" class="btn">
                Review Application
            </a> --}}
        </div>

        <div class="footer">
            <p>Thank you for your attention.</p>
            <p>&copy; {{ date('Y') }} <strong>Weblook International Private Limited</strong></p>
        </div>
    </div>

</body>
</html>
