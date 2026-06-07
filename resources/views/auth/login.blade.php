@extends('layouts.auth')

@section('content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">
            Login ke akunmu
        </h2>
        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">
                    Alamat Email
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Masukkan emailmu"
                       autocomplete="off"
                       value="{{ old('email') }}"
                >

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">
                    Password
                </label>

                <div class="input-group input-group-flat">
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Masukkan Password"
                           autocomplete="off"
                    >

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="mb-2">
                <label for="remember" class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input"/>
                    <span class="form-check-label">Remember me on this device</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                   Login
                </button>
            </div>
        </form>
    </div>
</div>
<div class="text-center text-secondary mt-3">
     <a href="{{ route('register') }}" tabindex="1">
        Daftar akun
    </a>

    <span class="form-label-description">
        <a href="{{ route('password.request') }}">Lupa Password</a>
    </span>
</div>
@endsection
