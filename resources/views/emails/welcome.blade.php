<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Weblook International Pvt Ltd.</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #444;
            background-color: #eef2f7;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            padding: 25px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-top: 8px solid #e53935;
        }
        .header {
            text-align: center;
            padding: 15px 0;
        }
        .header img {
            max-width: 180px;
        }
        h1 {
            color: #e53935;
            font-size: 2em;
            margin-bottom: 10px;
        }
        h2 {
            color: #2196f3;
            font-size: 1.6em;
            margin-top: 20px;
        }
        p {
            margin: 12px 0;
            color: #666;
        }
        .highlight {
            color: #e91e63;
            font-weight: bold;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background: #f9f9f9;
            margin: 8px 0;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #e53935;
        }
        a {
            color: #2196f3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            text-align: center;
            color: #555;
        }
        .footer p {
            margin: 6px 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 1.3em;
            margin-bottom: 10px;
            color: #d71f0a;
            border-bottom: 2px solid #e53935;
            display: inline-block;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
        <img src="https://ems.quicksite.asia/images/Weblook-300x65.png" alt="" width="170">
        </div>
        <h1>Welcome aboard, {{ $name }}!</h1>
        <p><B>We're thrilled to have you join the</B> <span class="highlight">Weblook International Pvt Ltd.</span> team!</p>
        
        <div class="section">
            <h2 class="section-title">Welcome Message</h2>
            <p><b>Welcome to the team! We're excited to see what you'll bring to the table and can't wait to start working with you.</b></p>
        </div>

        <div class="section">
            <h2 class="section-title">Your Details</h2>
            <ul>
                <li><strong>Your User Index:</strong> {{ $userId }}</li>
                <li><strong>Your Company Email:</strong> {{ $email }}</li>
                <li><strong>Employee Portal Access:</strong> <a href="{{ route('index') }}">{{ route('index') }}</a></li>
            </ul>
        </div>
        
        <!-- <div class="section">
            <h2 class="section-title">Meet Your Team</h2>
            <p>You're joining an incredible group of professionals. Here are a few people you'll be working closely with:</p>
            <ul>
                
                <li><strong>Hushantha, Senior Developer:</strong> Hushantha@weblook.com</li>
                <li><strong>Oshada, Senior Developer:</strong> Oshada@weblook.com</li>
                <li><strong>Chathuranga, Senior Developer:</strong> Chathuranga@weblook.com</li>
                <li><strong>HR:</strong> HR@weblook.com</li>
            </ul>
        </div> -->

        <!-- <div class="section">
            <h2 class="section-title">Next Steps</h2>
            <p>To help you get started, please complete the following tasks:</p>
            <ul>
                <li>Log in to the Employee Portal and update your profile.</li>
                <li>Review the company handbook available in the portal.</li>
                <li>Attend the orientation session scheduled on Monday at 10 AM.</li>
            </ul>
        </div> -->

        <p>We're excited to see what you'll accomplish here. Don't hesitate to reach out to your manager or HR if you have any questions.</p>
        <div class="footer">
            <p>Best Regards,</p>
            <p><b>HR Department</b></p>
            <p><b>Email:</b> hr@weblook.com</p>
        </div>
    </div>
</body>
</html>
