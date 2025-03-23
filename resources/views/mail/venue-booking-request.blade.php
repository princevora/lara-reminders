<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Booking Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #444;
        }
        .details {
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Venue Booking Request</h2>
        <p>Dear <strong>{{ $owner->name }}</strong>,</p>
        <p>I hope you are doing well. I would like to inquire about the availability of <strong>{{ $venue->name }}</strong> for an upcoming event.</p>

        <p>Please let me know the availability and pricing details. If needed, I would love to schedule a visit to discuss the arrangements.</p>

        <a href="#" class="button">Confirm Availability</a>

        <p class="footer">Thank you for your time. Looking forward to your response.</p>

        <p class="footer"><strong>Best regards,</strong><br>{{ $user->name }}</p>
    </div>

</body>
</html>
