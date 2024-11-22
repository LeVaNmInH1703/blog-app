@extends('layout.container')
@section('content-container')
    <div class="container">
        <h2>{{ __('public.Email Verification') }}</h2>
        <p>{{ __('public.Please check your email and click the verification button to complete your registration.') }}</p>
        <p class="message">
            {{ __('public.If you don’t see the email, please check your spam folder or click the button below to resend the verification email.') }}
        </p>
        <a id="resend-button" href="{{ route('resendVerificationEmail') }}"
            class="button">{{ __('public.Resend Verification Email') }}</a>
    </div>
    @if (env('APP_ENV') == 'local')
        <a href="{{ $urlForDev }}">Verify for dev</a>
    @endif
@section('style-container')
    <style>
        .container {
            background-color: #242527;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            /* Button color */
            color: #ffffff;
            /* White text on button */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
            color: #ffffff;

        }

        .message {
            margin-top: 20px;
            font-size: 1.1em;
        }

        .disabled {
            background-color: #ccc;
            /* Color when button is disabled */
            pointer-events: none;
        }
    </style>
@endsection
@section('script-container')
    <script>
        let resendButton = document.getElementById('resend-button');
        let cooldownTime = 30; // Cooldown time of 30 seconds
        let isOnCooldown = false;

        resendButton.addEventListener('click', function(event) {
            if (isOnCooldown) {
                event.preventDefault(); // Prevent default action
                alert(`Please wait ${cooldownTime} seconds before resending the verification email.`);
            } else {
                isOnCooldown = true;
                resendButton.classList.add('disabled'); // Disable button

                // Start countdown
                let countdown = cooldownTime;
                let countdownInterval = setInterval(function() {
                    countdown--;
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        isOnCooldown = false;
                        resendButton.classList.remove('disabled'); // Re-enable button
                        resendButton.innerText = 'Resend Verification Email'; // Reset button text
                    } else {
                        resendButton.innerText = `Resend Email (${countdown})`;
                    }
                }, 1000);
            }
        });
    </script>
@endsection
@endsection
