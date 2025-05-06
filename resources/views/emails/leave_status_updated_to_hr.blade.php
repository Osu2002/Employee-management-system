<!DOCTYPE html>
<html>
<head>
    <title>Leave Status Updated</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Leave Status Updated</h2>
        <p>A leave application has been updated. Below are the details:</p>

        <div class="details">
            <p><strong>Employee Name:</strong> {{ $employeeName }}</p>
            <p><strong>Leave Type:</strong> {{ $leaveType }}</p>
            <p><strong>From Date:</strong> {{ $fromDate }}</p>
            <p><strong>To Date:</strong> {{ $toDate }}</p>
            <p><strong>Status:</strong> {{ $status }}</p>
            @if($instructions)
                <p><strong>Instructions:</strong> {{ $instructions }}</p>
            @else
                <p><strong>Instructions:</strong> No additional instructions provided.</p>
            @endif
        </div>

        <p>Thank you!</p>
        <p><strong>HR System</strong></p>
    </div>
</body>
</html>
