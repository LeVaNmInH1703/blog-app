<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            font-size: 12px;
            color: #999;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>{{ __('public.Forgot password') }}</h1>
    <p>{{ __('public.Hi') }} {{ $user->name }}!</p>
    <p>{{ __('public.To create new your password, please click the button below:') }}</p>
    <a href="{{ $url }}" class="button">{{ __('public.Create new password') }}</a>
    <p>{{ __('public.If you don\'t have a blog app account, you can ignore this email.') }}</p>
    <div class="footer">
        <p>{{ __('public.Thank you for using our service!') }}</p>
    </div>
</div>

</body>
</html>