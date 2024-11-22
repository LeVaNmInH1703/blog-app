<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Thực Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ __("public.Verify Email") }}</h2>
        <p>{{ __("public.Hi") }} {{ $user->name }},</p>
        <p>{{ __("public.Thank you for registering an account at ") }} {{ config('app.name') }}. {{ __("public.To complete the registration process, please verify your email address by clicking the button below:") }}</p>
        <a href="{{ $verificationUrl }}" class="button">{{ __("public.Verify Email") }}</a>
        <p>{{ __("public.If you did not create this account, you can ignore this email.") }}</p>
        <p>{{ __("public.Best regards") }},<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>