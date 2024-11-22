@extends('layout.container')
@section('content-container')
    <div class="container register-container">
        <div class="row justify-content-center">
            <div class="col-md-12 p-4">
                <h1 class="text-center">{{ __('public.Register') }}</h1>
                <form id="registerForm" action="/register" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">{{ __('public.Name') }}</label>
                        <input type="text" spellcheck="false" class="form-input" id="name" name="name"
                            placeholder="{{ __('public.Enter your name') }}" required
                            value="{{ session('name') ?? old('name') }}">
                        <x-show-errors-component :errors=$errors :name="'name'" />
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="email">{{ __('public.Email') }}</label>
                        <input type="email" class="form-input" autocomplete="new-password" id="email" name="email"
                            placeholder="{{ __('public.Enter an email') }}" required
                            value="{{ session('email') ?? old('email') }}">
                        <x-show-errors-component :errors=$errors :name="'email'" />
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="password">{{ __('public.Password') }}</label>
                        <input type="password" class="form-input" id="password" autocomplete="new-password" name="password"
                            placeholder="{{ __('public.Enter an password') }}" required>
                        <x-show-errors-component :errors=$errors :name="'password'" />
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="confirm">{{ __('public.Confirm') }}</label>
                        <input type="password" class="form-input" id="confirm" name="confirm"
                            placeholder="{{ __('public.Enter confirm password') }}" required>
                        <x-show-errors-component :errors=$errors :name="'confirm'" />
                    </div>
                    <br>
                    <div class="avatar">
                        <span>{{ __('public.Choose an image for your avatar or') }} <label for="fileInput" class="btn-link"
                                style="cursor: pointer;">{{ __('public.open your image') }}</label></span>

                        <img src="#" alt="Preview" id="filePreview" hidden class="img-thumbnail"> <br>
                        <input type="file" accept="image/*" hidden name="fileAvatar" id="fileInput">
                        <x-show-errors-component :errors=$errors :name="'fileAvatar'" />
                        <input type="hidden" name='avatar' id="img-selected" value="1">
                        <div class="image-container p-4">
                            @for ($i = 1; $i <= 8; $i++)
                                <img src="{{ asset('images/avatar' . $i . '.png') }}" alt="Image {{ $i }}"
                                    data-value="{{ $i }}">
                            @endfor
                        </div>
                    </div>
                    <x-show-errors-component :errors=$errors :name="'incorrect'" />

                    <button type="submit"
                        class="btn btn-outline-primary btn-block w-100 p-2">{{ __('public.Register') }}</button>
                </form>
                <div id="message" class="text-danger text-center mt-2"></div>
                <div class="text-center mt-3">
                    <p>{{ __('public.You already have an account?') }} <a href="{{ route('login') }}"
                            class="register-link">{{ __('public.Login') }}</a></p>
                </div>
            </div>
        </div>
    </div>
@section('script-container')
    <script>
        const fileInput = document.getElementById('fileInput');
        const previewImage = document.getElementById('filePreview');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.hidden = false;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        const images = document.querySelectorAll('.image-container img');
        images[0].classList.add('selected')
        let selectedValue = 1;
        const input = document.querySelector('#img-selected');
        input.value = selectedValue;
        images.forEach(img => {
            img.addEventListener('click', () => {
                selectedValue = img.getAttribute('data-value');
                images.forEach(i => i.classList.remove('selected'));
                img.classList.add('selected');
                input.value = selectedValue;
            });
        });
    </script>
@endsection
@section('style-container')
    <style>
        .image-container img {
            width: 50px;
            height: auto;
            cursor: pointer;
        }

        .image-container img.selected {
            border: 4px solid blue;
        }

        .register-container {
            margin-top: 10vh;
            max-width: 600px;
            margin-right: auto;
            margin-left: auto;
            background: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            padding: 20px;
        }

        .register-container label {
            font-size: 18px;
            margin: 10px 0px 0px 0px;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            background-color: inherit;
            color: inherit;
        }
    </style>
@endsection
@endsection
