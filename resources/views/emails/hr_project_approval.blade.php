<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Submission Pending Approval</title>
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
            background-color: #d9534f;
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
            color: #d9534f;
            margin-bottom: 10px;
        }

        .email-body p {
            margin: 10px 0;
            font-size: 1rem;
        }

        .email-body ul {
            list-style-type: none;
            padding: 0;
        }

        .email-body ul li {
            margin: 5px 0;
            font-size: 1rem;
        }

        .email-body ul li strong {
            color: #d9534f;
        }

        .email-footer {
            text-align: center;
            padding: 15px;
            background-color: #f5f5f5;
            font-size: 0.9rem;
            color: #777;
        }

        .email-footer a {
            color: #d9534f;
            text-decoration: none;
            font-weight: bold;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            Project Submission Pending Approval
        </div>
        <div class="email-body">
            <h1>Dear HR,</h1>
            <p>Employee <strong>{{ $employeeName }}</strong> has submitted a project. Please review and approve it.</p>
            <ul>
                <li><strong>Project Name:</strong> {{ $projectDetails['project_name'] }}</li>
                <li><strong>Description:</strong> {{ $projectDetails['project_description'] ?? 'N/A' }}</li>
                <li><strong>Skills:</strong> {{ implode(', ', json_decode($projectDetails['skills'], true) ?? []) }}</li>
                <li><strong>Contributors:</strong> {{ implode(', ', json_decode($projectDetails['contributors'], true) ?? []) }}</li>
            </ul>
            <p>Thank you,</p>
            <p>The HR System</p>
        </div>
        <div class="email-footer">
            &copy; 2025 Weblook International Pvt Ltd. All rights reserved. <br>
        </div>
    </div>
</body>
</html>
