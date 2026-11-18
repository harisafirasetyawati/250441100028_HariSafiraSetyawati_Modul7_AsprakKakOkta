<x-guest-layout>
    <style>
        .login-card-container {
            font-family: 'Poppins', sans-serif;
            color: #493728;
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: #654a32;
            text-align: center;
            margin-bottom: 4px;
        }

        .login-subtitle {
            font-size: 13px;
            color: #8b7865;
            text-align: center;
            margin-bottom: 24px;
        }

        .earthy-label {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #654a32 !important;
        }

        .earthy-input {
            width: 100%;
            border: 1px solid #e3cdb5 !important;
            background-color: #fffdf9 !important;
            color: #493728 !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            font-size: 14px !important;
            outline: none !important;
            transition: all 0.2s ease;
        }

        .earthy-input:focus {
            border-color: #806548 !important;
            box-shadow: 0 0 0 3px rgba(128, 101, 72, 0.15) !important;
        }

        .btn-earthy {
            width: 100%;
            background-color: #806548 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding: 11px !important;
            border-radius: 8px !important;
            border: none !important;
            cursor: pointer !important;
            font-size: 14px !important;
            text-align: center;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-earthy:hover {
            background-color: #654a32 !important;
        }

        .register-prompt {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #f0e4d6;
            text-align: center;
            font-size: 13px;
            color: #8b7865;
        }

        .register-link {
            color: #654a32 !important;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .register-link:hover {
            text-decoration: underline;
            color: #493728 !important;
        }
    </style>

    <div class="login-card-container">
        <h1 class="login-title">Buat Akun Baru</h1>
        <p class="login-subtitle">Daftar ke Sistem Perpustakaan 250441100028</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="earthy-label" />
                <x-text-input id="name" class="block mt-1 w-full earthy-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap anda" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" class="earthy-label" />
                <x-text-input id="email" class="block mt-1 w-full earthy-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Masukkan email anda" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="earthy-label" />

                <x-text-input id="password" class="block mt-1 w-full earthy-input"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="earthy-label" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full earthy-input"
                                type="password"
                                name="password_confirmation"
                                placeholder="••••••••"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-5">
                <button type="submit" class="btn-earthy">
                    {{ __('Register') }}
                </button>
            </div>

            <div class="register-prompt">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="register-link">
                    Masuk di sini
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>