<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Event Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 20px;
            color: #333;
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            color: #555;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #888;
        }
        .button {
            display: inline-block;
            background-color: #007BFF;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <strong>📢 Event Reminder</strong>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $user->name }}</strong>,</p>
        <p>This is a friendly reminder about the upcoming event:</p>

        <p><strong>📌 Event:</strong> {{ $event->title }} </p>
        @php
            $date = Carbon\Carbon::now()->addDays(rand(1, 365))->format('D-m-Y');
        @endphp
        <p><strong>📅 Date:</strong> {{ $date }} </p>
        <p><strong>📝 Description:</strong> {{ $event->description }} </p>

        <p>We look forward to your presence!</p>
    </div>

    <div class="footer">
        <p>Best regards, <br> The Event Team</p>
    </div>
</div>

</body>
</html>
